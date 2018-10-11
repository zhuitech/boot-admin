<?php

namespace ZhuiTech\BootAdmin\Console;

use Encore\Admin\Auth\Database\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Auth\Database\Administrator;

class Clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhuitech:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清空以下数据:菜单';

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
        Menu::truncate();
    }
}
