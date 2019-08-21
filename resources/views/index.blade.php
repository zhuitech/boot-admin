@php
    $dmp_config = settings('dmp_config');
@endphp
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Admin::title() }}</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="{{ asset($dmp_config['shortcut_icon'] ?? '') }}" type="image/x-icon" />

    {!! Admin::css() !!}

    <!-- REQUIRED CSS BY iBrand-->
    <link rel="stylesheet" href="{{ admin_asset ("/vendor/boot-admin/libs/webuploader-0.1.5/webuploader.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/boot-admin/inspinia/css/animate.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/boot-admin/inspinia/css/style.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/boot-admin/inspinia/css/main.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/boot-admin/inspinia/css/admin.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/boot-admin/css/plugins/iCheck/custom.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/boot-admin/css/style.css") }}">
    <link rel="stylesheet" href="//at.alicdn.com/t/font_u5095o4vzog8pvi.css">

    <!-- REQUIRED JS SCRIPTS -->
    <script src="{{ Admin::jQuery() }}"></script>
    <script src="{{ admin_asset ("/vendor/boot-admin/libs/jquery.form.min.js") }}"></script>
    {!! Admin::headerJs() !!}

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
        window.AppUrl = "{{env('APP_URL')}}";
        window._token = "{{ csrf_token() }}";
    </script>
</head>

<body class="hold-transition {{config('admin.skin')}} {{join(' ', config('admin.layout'))}}">

<div id="wrapper">
    @include('admin::partials.sidebar')
    <div id="page-wrapper" class="gray-bg dashbard-1">
        @include('admin::partials.header')
        <div class="row wrapper wrapper-content animated fadeInRight" style="padding-top: 0;">
            <div class="row">
                <div class="col-lg-12">
                    <div id="pjax-container">
                        {!! Admin::style() !!}
                        <div id="app">
                            @yield('content')
                        </div>
                        {!! Admin::script() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="pull-right">
                技术支持：{{ $dmp_config['technical_support'] }}
            </div>
            <div>
                <strong>Copyright</strong> {{ $dmp_config['copyright'] }}
            </div>
        </div>
    </div>
</div>

{!! Admin::html() !!}

<script>
    function LA() {}
    LA.token = "{{ csrf_token() }}";
</script>

<!-- REQUIRED JS SCRIPTS -->
{!! Admin::js() !!}

<!-- REQUIRED JS SCRIPTS BY iBrand-->
<script src="{{ admin_asset ("/vendor/boot-admin/libs/webuploader-0.1.5/webuploader.js") }}"></script>
<script src="{{ admin_asset("/vendor/boot-admin/inspinia/js/plugins/metisMenu/jquery.metisMenu.js") }}"></script>
<script src="{{ admin_asset("/vendor/boot-admin/inspinia/js/inspinia.js") }}"></script>
<script src="{{ admin_asset ("/vendor/boot-admin/libs/plugins.js") }}"></script>
<script src="{{ admin_asset ("/vendor/boot-admin/libs/active.js") }}"></script>

</body>
</html>