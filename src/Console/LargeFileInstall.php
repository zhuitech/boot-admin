<?php

namespace ZhuiTech\BootAdmin\Console;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Console\Command;

class LargeFileInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhuitech:largefile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成大文件上传目录';


    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $rootDir = Config::get('aetherupload.root_dir');
	    $disk = Storage::disk('local');

        try {

            if ( ! $disk->exists($rootDir) ) {
	            $disk->makeDirectory($rootDir . DIRECTORY_SEPARATOR . '_header');
                $this->info('Root directory "' . $rootDir . '" has been created.');
            }

            $directories = array_map(function ($directory) {
                return basename($directory);
            }, $disk->directories($rootDir));

            $groupDirs = array_map(function ($v) {
                return $v['group_dir'];
            }, Config::get('aetherupload.groups'));

            foreach ( $groupDirs as $groupDir ) {
                if ( in_array($groupDir, $directories) ) {
                    continue;
                } else {
                    if ( $disk->makeDirectory($rootDir . DIRECTORY_SEPARATOR . $groupDir) ) {
                        $this->info('Directory "' . $rootDir . DIRECTORY_SEPARATOR . $groupDir . '" has been created.');
                    } else {
                        $this->error('Fail to create directory "' . $rootDir . DIRECTORY_SEPARATOR . $groupDir . '".');
                    }
                }
            }

            $this->info('Group-Directory List:');

            foreach ( Config::get('aetherupload.groups') as $groupName => $groupArr ) {
                if ( $disk->exists($rootDir . DIRECTORY_SEPARATOR . $groupArr['group_dir']) ) {
                    $this->info($groupName . '-' . $groupArr['group_dir']);
                }
            }

        } catch ( \Exception $e ) {

            $this->error($e->getMessage());
        }

    }
}
