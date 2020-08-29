<?php

namespace ZhuiTech\BootAdmin\Models;

use Eloquent;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $status
 * @property-read Collection|Client[] $clients
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|Role[] $roles
 * @property-read Collection|Token[] $tokens
 * @method static Builder|Staff newModelQuery()
 * @method static Builder|Staff newQuery()
 * @method static Builder|Staff query()
 * @method static Builder|Staff whereAvatar($value)
 * @method static Builder|Staff whereCreatedAt($value)
 * @method static Builder|Staff whereEmail($value)
 * @method static Builder|Staff whereId($value)
 * @method static Builder|Staff whereMobile($value)
 * @method static Builder|Staff whereName($value)
 * @method static Builder|Staff wherePassword($value)
 * @method static Builder|Staff whereRememberToken($value)
 * @method static Builder|Staff whereStatus($value)
 * @method static Builder|Staff whereUpdatedAt($value)
 * @method static Builder|Staff whereUsername($value)
 * @mixin Eloquent
 */
class Staff extends Administrator
{
	use HasApiTokens;
}