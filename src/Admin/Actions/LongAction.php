<?php

namespace ZhuiTech\BootAdmin\Admin\Actions;

use Encore\Admin\Actions\Action;
use Encore\Admin\Admin;
use Illuminate\Http\Request;

class LongAction extends Action
{
	protected $title = '批量操作';

	public function response()
	{
		// 使用自定义 response
		if (is_null($this->response)) {
			$this->response = new Response();
		}

		return parent::response();
	}

	public function handleActionPromise()
	{
		parent::handleActionPromise();

		$resolve = <<<SCRIPT

		var longActionResolver = function (data) {			
            var response = data[0];
            var target   = data[1];

            if (response.then.action == 'next') {
                var value = response.then.value;
                $.admin.swal({
                    title: '正在' + value.title,
                    html: '当前进度 <strong></strong>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    
                    onBeforeOpen: () => {
					    Swal.showLoading();
				        nextProcess(value, target).then(nextResolver).catch(actionCatcher);
					},
                });
            } else {
                Swal.close();
                actionResolver(data);
            }
        };
				        
        var nextResolver = function (data) {			
            var response = data[0];
            var target   = data[1];
            if (response.then.action == 'next') {
                value = response.then.value;
	            Swal.getContent().querySelector('strong').textContent = Math.round(value.page / value.total * 100) + '%'
	            nextProcess(value, target).then(nextResolver).catch(actionCatcher);
            } else {
                Swal.close();
                actionResolver(data);
            }
        };

		function nextProcess(value, target) {
			return new Promise(function (resolve,reject) {
		        var data = {}
	            Object.assign(data, {
	                _token: $.admin.token,
	                _action: value.action,
	                next: value
	            });
	
	            $.ajax({
	                method: value.method,
	                url: value.url,
	                data: data,
	                success: function (data) {
                        resolve([data, target]);
	                },
	                error:function(request){
	                    reject(request);
	                }
	            });
	        });
		}
SCRIPT;
		Admin::script($resolve);

		return <<<SCRIPT
		process.then(longActionResolver).catch(actionCatcher);
SCRIPT;
	}

	public function handle(Request $request)
	{
		$next = $request->get('next', []);

		if (empty($next)) {
			$next = $this->first();
			$next += [
				'title' => $this->title,
				'action' => $this->getCalledClass(),
				'method' => $this->method,
				'url' => $this->getHandleRoute(),
			];
		}

		try {
			if ($next['page'] < $next['total']) {
				$this->next($next);
				$next['page'] += 1;
				return $this->response()->next($next);
			} else {
				return $this->last();
			}
		} catch (\Exception $exception) {
			return $this->response()->error($exception->getMessage())->refresh();
		}
	}

	protected function first()
	{
		return ['total' => 1, 'page' => 0];
	}

	protected function next($data)
	{

	}

	protected function last()
	{
		return $this->response()->success('操作成功')->refresh();
	}
}