<?php


namespace ZhuiTech\BootAdmin\Console;


use Encore\Admin\Auth\Database\Menu;
use Illuminate\Console\Command;
use ZhuiTech\BootLaravel\Helpers\RestClient;

class ServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhuitech:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '安装服务模块';

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
        $rootOrder = Menu::where('parent_id', 0)->max('order');
        if (empty($root = Menu::where(['title' => '插件', 'parent_id' => 0])->first())) {
            $root = Menu::create([
                'parent_id' => 0,
                'order' => ++$rootOrder,
                'title' => '插件',
                'icon' => 'fa-plug',
                'uri' => '/svc',
            ]);
        }

        $lastOrder = $rootOrder * 100;
        $menus = RestClient::server('service')->get('api/svc/system/menus');

        if ($menus['status'] == true) {
            foreach ($menus['data'] as $menu) {
                if (empty($parent = Menu::where(['title' => $menu['icon'] ?? '', 'parent_id' => $root->id])->first())) {
                    $parent = Menu::create([
                        'parent_id' => $root->id,
                        'order' => $lastOrder++,
                        'title' => $menu['title'] ?? '',
                        'icon' => $menu['icon'] ?? '',
                        'uri' => $menu['uri'] ?? '',
                    ]);

                    if (!empty($menu['children'])) {
                        foreach ($menu['children'] as $child) {
                            Menu::create([
                                'parent_id' => $parent->id,
                                'order' => $lastOrder++,
                                'title' => $child['title'] ?? '',
                                'icon' => $child['icon'] ?? '',
                                'uri' => $child['uri'] ?? '',
                            ]);
                        }
                    }
                }
            }
        }
    }
}