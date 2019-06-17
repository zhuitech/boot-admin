@php
    $dmp_config = settings('dmp_config');
@endphp
<!-- 左侧头像及菜单-->
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" style="width: 64px;" class="img-circle"
                                 src="{{ Admin::user()->avatar }}"/>
                             </span>
                </div>

                <div class="logo-element">
                    <img src="{{ asset($dmp_config['backend_logo']) }}" width="36px">
                </div>
            </li>

            @each('admin::partials.menu', BackendMenu::sideMenu(), 'item')

        </ul>
    </div>
</nav>
<!-- 左侧头像及菜单-->