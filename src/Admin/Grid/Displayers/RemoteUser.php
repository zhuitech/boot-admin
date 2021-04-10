<?php


namespace ZhuiTech\BootAdmin\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;
use ZhuiTech\BootLaravel\Remote\Service\UserAccount;

/**
 * 显示用户连接
 * Class User
 * @package ZhuiTech\Shop\User\Admin\Displayers
 */
class RemoteUser extends AbstractDisplayer
{
	/**
	 * Display method.
	 *
	 * @param array $option
	 * @return mixed
	 */
	public function display($option = [])
	{
		return self::render($this->value, $option);
	}

	/**
	 * @param $user UserAccount
	 * @param array $option
	 * @return string
	 */
	public static function render($user, $option = [])
	{
		$option += ['avatar' => true, 'name' => true, 'mobile' => false];
		if (empty($user)) {
			return '';
		}

		$result = '';
		$url = admin_url("svc/user/accounts/{$user->id}/edit");

		if ($option['avatar']) {
			$result .= <<<EOT
<a href='$url' target='_blank'><img src="{$user->avatar}" width="28"></a> 
EOT;
		}

		if ($option['name']) {
			$name = $user->name ?? '';
			if (empty($name)) $name = $user->nickname ?? '';
			if (empty($name)) $name = $user->mobile ?? '';

			$result .= <<<EOT
<a href='$url' target='_blank'>{$name}</a> 
EOT;
		}

		if ($option['mobile']) {
			$result .= <<<EOT
 <i class="fa fa-mobile"></i>{$user->mobile}
EOT;
		}

		return $result;
	}
}