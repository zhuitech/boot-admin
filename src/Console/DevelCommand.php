<?php

namespace ZhuiTech\BootAdmin\Console;

use Encore\Admin\Auth\Database\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Auth\Database\Administrator;

class DevelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhuitech:devel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '切换开发模式';

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
        // 无需手机验证
        settings(['admin_sms_login_status' => 0]);
    }
}
