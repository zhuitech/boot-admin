<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 2018/10/21
 * Time: 23:35
 */

namespace ZhuiTech\BootAdmin\Repositories;

use Doctrine\DBAL\Driver\PDOException;
use iBrand\Component\Setting\Repositories\EloquentSetting;

class SettingRepository extends EloquentSetting
{
    public function getSetting($key, $input = null)
    {
        try {
            return parent::getSetting($key, $input);
        }
        catch (\PDOException $exception) {
            return $input;
        }
    }

    public function allToArray()
    {
        try {
            return parent::allToArray();
        }
        catch (\PDOException $exception) {
            return [];
        }
    }
}