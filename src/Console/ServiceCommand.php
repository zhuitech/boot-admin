<?php


namespace ZhuiTech\BootAdmin\Console;


use Encore\Admin\Auth\Database\Menu;
use Illuminate\Console\Command;
use ZhuiTech\BootAdmin\Admin\Extension;
use ZhuiTech\BootLaravel\Helpers\RestClient;

class ServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhuitech:svc-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '链接服务模块';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->menus();
    }

    private function menus()
    {
        $menus = RestClient::server('service')->get('api/svc/system/menus');
        
        foreach ($menus['data'] ?? [] as $root) {
            Extension::createMenuTree($root);
        }
    }
}