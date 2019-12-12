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

  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/font-awesome/css/font-awesome.min.css") }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css") }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/iCheck/square/blue.css") }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ admin_asset("/vendor/laravel-admin/toastr/build/toastr.min.css") }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page" @if(config('admin.login_background_image'))style="background: url({{config('admin.login_background_image')}}) no-repeat;background-size: cover;"@endif>
<div class="login-box">
  <div class="login-logo">
    <a href="{{ admin_url('/') }}"><b>{{config('admin.name')}}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">{{ trans('admin.login') }}</p>

    <form id="login_form" action="{{ admin_url('auth/login') }}" method="post" onsubmit="return check(this)">
      <div class="form-group has-feedback {!! !$errors->has('username') ?: 'has-error' !!}">

        @if($errors->has('username'))
          @foreach($errors->get('username') as $message)
            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label><br>
          @endforeach
        @endif

        <input type="text" class="form-control" placeholder="{{ trans('admin.username') }}" name="username" value="{{ old('username') }}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback {!! !$errors->has('password') ?: 'has-error' !!}">

        @if($errors->has('password'))
          @foreach($errors->get('password') as $message)
            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label><br>
          @endforeach
        @endif

        <input type="password" class="form-control" placeholder="{{ trans('admin.password') }}" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      @if(config('backend.sms_login'))
        <div class="form-group has-feedback {!! !$errors->has('code') ?: 'has-error' !!}">

          @if($errors->has('code'))
            @foreach($errors->get('code') as $message)
              <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label><br>
            @endforeach
          @endif

          <input type="text" class="form-control" placeholder="验证码" name="code" value="{{ old('code') }}" style="width: 220px; display: inline-block;">
          <button id="send-verify" type="button" class="btn btn-default btn-flat" data-target="login" data-status="0">发送验证码</button>
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

<!-- jQuery 2.1.4 -->
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js")}} "></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js")}}"></script>
<!-- iCheck -->
<script src="{{ admin_asset("vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>
<!-- Toastr -->
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
    if(form.username.value==''){
      toastr.warning("请输入邮箱或用户名");
      return false;
    }
    if(form.password.value==''){
      toastr.warning("请输入密码");
      return false;
    }
    return true;
  }

  window.AppUrl = "{{env('APP_URL')}}";
  window._token = "{{ csrf_token() }}";
  var postUrl = '{{env('APP_URL')}}/admin/auth/mobile';

  @if(config('backend.sms_login'))
  $(document).ready(function () {
    // 发送验证码
    $('#send-verify').on('click', function () {
      var el = $(this);
      var target = el.data('target');
      var mobileReg = /^(?=\d{11}$)^1(?:3\d|4[57]|5[^4\D]|66|7[^249\D]|8\d|9[89])\d{8}$/;

      if(target == 'login'){ //  如果是登录
        $.ajax({
          type: 'post',
          data: {
            username: $('input[name="username"]').val(),
            _token:_token
          },
          url: postUrl,
          success: function (res) {
            if (res.status) {
              $('input[name="mobile"]').val(res.data.mobile);
              sendCode(el,res.data.mobile);
            } else {
              toastr.error(res.msg);
            }
          },
          error: function () {
            toastr.error('账号验证失败');
          }
        })

      } else {
        var mobile = $('input[data-type=' + target + ']').val();
        if (mobile == ''){
          toastr.error('请输入手机号码');
          return
        }
        if (!mobileReg.test(mobile)) {
          toastr.error('请输入正确的手机号码');
        } else {
          sendCode(el,mobile);
        }
      }
    });

    //发送验证码方法
    function sendCode(el,mobile) {
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
        url: AppUrl+"/api/svc/sms/verify?_token="+_token,
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
            },1000)
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
