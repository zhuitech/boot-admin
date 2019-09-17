<?php

namespace ZhuiTech\BootAdmin\Models;

use Encore\Admin\Auth\Database\Administrator;
use Laravel\Passport\HasApiTokens;

/**
 * ZhuiTech\BootAdmin\Models\Staff
 *
 * @property int $id
 * @property string $username
 * @property string|null $email
 * @property string $password
 * @property string $name
 * @property string|null $mobile
 * @property string $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\Encore\Admin\Auth\Database\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Encore\Admin\Auth\Database\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff query()
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ZhuiTech\BootAdmin\Models\Staff whereUsername($value)
 * @mixin \Eloquent
 */
class Staff extends Administrator
{
    use HasApiTokens;
}