<?php

namespace ZhuiTech\BootAdmin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use ZhuiTech\BootAdmin\Seeds\AdminTableSeeder;

class InitialCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhuitech:admin-initial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化Admin数据';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 菜单
        $this->call('db:seed', ['--class' => AdminTableSeeder::class]);

        // 需要手机验证
        settings(['admin_sms_login_status' => 1]);

        // 基础配置
        settings(['dmp_config' => [
            "page_title" => "欢迎登录后台",
            "short_title" => "后台",
            "copyright" => "© 2016-2018",
            "technical_support" => "上海追数网络科技有限公司",
            "login_logo" => "vendor/boot-admin/img/logo.png",
            "backend_logo" => "vendor/boot-admin/img/logo-mini.png",
            "shortcut_icon" => "vendor/boot-admin/img/icon.png",
            "setting-cache" => "0"
        ]]);
    }
}
