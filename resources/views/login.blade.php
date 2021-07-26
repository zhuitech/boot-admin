<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{config('admin.title')}} | {{ trans('admin.login') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    @if(!is_null($favicon = Admin::favicon()))
        <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/font-awesome/css/font-awesome.min.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/iCheck/square/blue.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/toastr/build/toastr.min.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("vendor/boot-admin/css/boot-admin.css") }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .row {
            margin: 0;
        }

        .col-md-12, .col-md-3, .col-xs-8, .col-xs-4 {
            padding: 0;
        }

        @media screen and (min-width: 1000px) and (max-width: 1150px) {
            .col-lg-3,
            .col-lg-9 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media screen and (min-width: 1151px) and (max-width: 1300px) {
            .col-lg-3 {
                flex: 0 0 40%;
                max-width: 40%;
            }

            .col-lg-9 {
                flex: 0 0 60%;
                max-width: 60%;
            }
        }

        @media screen and (min-width: 1301px) and (max-width: 1700px) {
            .col-lg-3 {
                flex: 0 0 35%;
                max-width: 35%;
            }

            .col-lg-9 {
                flex: 0 0 65%;
                max-width: 65%;
            }
        }

        .login-page {
            height: auto;
        }

        .login-main {
            position: relative;
            display: flex;
            min-height: 100vh;
            flex-direction: row;
            align-items: stretch;
            margin: 0;
        }

        .login-main .login-page {
            background-color: #fff;
        }

        .login-main .card {
            box-shadow: none;
        }

        .login-main .auth-brand {
            margin: 4rem 20px;
            font-size: 26px;
            width: 325px;
        }

        @media (max-width: 576px) {
            .login-main .auth-brand {
                width: 90%;
                margin-left: 24px
            }
        }

        .login-main .login-logo {
            font-size: 2.1rem;
            font-weight: 300;
            margin-bottom: 0.9rem;
            text-align: left;
            margin-left: 20px;
        }

        .login-main .login-box-msg {
            margin: 0;
            padding: 0 0 20px;
            font-size: 0.9rem;
            font-weight: 400;
            text-align: left;
        }

        .login-main .btn {
            width: 100%;
        }

        .login-page-right {
            padding: 6rem 3rem;
            flex: 1;
            position: relative;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.3);
            text-align: center !important;
            background: url({{$wallpaper}}) center;
            background-size: cover;
        }

        .login-description {
            position: absolute;
            margin: 0 auto;
            padding: 0 1.75rem;
            bottom: 3rem;
            left: 0;
            right: 0;
        }

        .content-front {
            position: absolute;
            left: 0;
            right: 0;
            height: 100vh;
            background: rgba(0, 0, 0, .1);
            margin-top: -6rem;
        }

        .login-description a {
            color: white;
        }
    </style>
</head>

<body>

<div class="row login-main">
    <div class="col-lg-3 col-12 bg-white">
        <div class="login-page">

            <div class="login-box">
                <div class="auth-brand">
                    @if($logo = config('admin.login_logo'))
                        <img src="{{storage_url($logo)}}">
                    @else
                        {{config('admin.name')}}
                    @endif
                </div>

                <div class="login-logo mb-2">
                    <h4 class="mt-0">让管理更高效</h4>
                    <p class="login-box-msg mt-1 mb-1">Welcome back, please login to your account.</p>
                </div>

                <!-- /.login-logo -->
                <div class="login-box-body">
                    <form id="login_form" action="{{ admin_url('auth/login') }}" method="post" onsubmit="return check(this)">
                        <div class="form-group has-feedback {!! !$errors->has('username') ? '' : 'has-error' !!}">
                            @if($errors->has('username'))
                                @foreach($errors->get('username') as $message)
                                    <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br>
                                @endforeach
                            @endif
                            <input type="text" class="form-control" placeholder="{{ trans('admin.username') }}" name="username" value="{{ old('username') }}">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>

                        <div class="form-group has-feedback {!! !$errors->has('password') ? '' : 'has-error' !!}">
                            @if($errors->has('password'))
                                @foreach($errors->get('password') as $message)
                                    <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br>
                                @endforeach
                            @endif
                            <input type="password" class="form-control" placeholder="{{ trans('admin.password') }}" name="password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>

                        @if(config('boot-admin.sms_login'))
                            <div class="form-group has-feedback {!! !$errors->has('code') ? '' : 'has-error' !!}">
                                @if($errors->has('code'))
                                    @foreach($errors->get('code') as $message)
                                        <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br>
                                    @endforeach
                                @endif
                                <div class="row">
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" placeholder="验证码" name="code" value="{{ old('code') }}">
                                    </div>
                                    <div class="col-xs-4">
                                        <button id="send-verify" type="button" class="btn btn-default btn-block btn-flat" data-target="login" data-status="0">
                                            发送验证码
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-xs-8">
                                @if(config('admin.auth.remember'))
                                    <div class="checkbox icheck">
                                        <label>
                                            <input type="checkbox" name="remember" value="1" {{ (!old('username') || old('remember')) ? 'checked' : '' }}>
                                            {{ trans('admin.remember_me') }}
                                        </label>
                                    </div>
                                @endif
                            </div>
                            <!-- /.col -->
                            <div class="col-xs-4">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('admin.login') }}</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                </div>
                <!-- /.login-box-body -->
            </div>
            <!-- /.login-box -->
        </div>
    </div>
    <div class="col-lg-9 col-12 login-page-right">
        <div class="content-front"></div>
        <div class="login-description">
            <p class="lead">

            </p>
            <p>
                {!! config('boot-admin.copyright') !!}
            </p>
        </div>
    </div>
</div>

<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js")}} "></script>
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js")}}"></script>
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>
<script src="{{ admin_asset("/vendor/laravel-admin/toastr/build/toastr.min.js")}}"></script>
<script>
	$(function () {
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%' // optional
		});
	});
</script>

<script>
	function check(form) {
		if (form.username.value == '') {
			toastr.warning("请输入用户名");
			return false;
		}

		if (form.password.value == '') {
			toastr.warning("请输入密码");
			return false;
		}

		return true;
	}

	window.AppUrl = "{{env('APP_URL')}}";
	window._token = "{{ csrf_token() }}";

    @if(config('boot-admin.sms_login'))
	$(document).ready(function () {
		// 发送验证码
		$('#send-verify').on('click', function () {
			var el = $(this);
			$.ajax({
				type: 'post',
				data: {
					username: $('input[name="username"]').val(),
					_token: _token
				},
				url: AppUrl + '/admin/auth/mobile',
				success: function (res) {
					if (res.status) {
						$('input[name="mobile"]').val(res.data.mobile);
						sendCode(el, res.data.mobile);
					} else {
						toastr.error(res.msg);
					}
				},
				error: function () {
					toastr.error('账号验证失败');
				}
			})
		});

		// 发送验证码方法
		function sendCode(el, mobile) {
			if (el.data('status') != 0) {
				return
			}
			el.text('正在发送...');
			el.data('status', '1');
			$.ajax({
				type: 'POST',
				data: {
					mobile: mobile,
					access_token: _token
				},
				url: AppUrl + "/api/sms/verify?_token=" + _token,
				success: function (data) {
					if (data.status) {
						$('input[name="access_token"]').val(_token);
						var total = 60;
						var message = '请等待{#counter#}秒';
						el.text(message.replace(/\{#counter#}/g, total));
						var timer = setInterval(function () {
							total--;
							el.text(message.replace(/\{#counter#}/g, total));

							if (total < 1) {
								el.data('status', '0');
								el.text('发送验证码');
								clearInterval(timer);
							}
						}, 1000)
					} else {
						el.data('status', '0');
						el.text('发送验证码');
						toastr.error('短信发送失败！');
					}
				},
				error: function () {
					el.data('status', '0');
					el.text('发送验证码');
					toastr.error('短信发送失败！');
				}
			})
		};
	});
    @endif

</script>
</body>
</html>
