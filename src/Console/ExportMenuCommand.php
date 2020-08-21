<?php


namespace ZhuiTech\BootAdmin\Console;


use Encore\Admin\Facades\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ExportMenuCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'zhuitech:export-menu';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '导出菜单';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$data = Admin::menu();
		$data = $this->filter($data);

		$content = var_export_new($data, true);
		file_put_contents(storage_path('menu.php'), "<?php \r\n return {$content};");
	}

	private function filter($data)
	{
		foreach ($data as $i => $item) {
			$data[$i] = Arr::only(array_filter($item), ['title', 'icon', 'uri', 'blank']);

			if (isset($item['children'])) {
				$data[$i]['children'] = $this->filter($item['children']);
			}
		}
		return $data;
	}
}