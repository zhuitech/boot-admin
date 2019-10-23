<div class="modal-dialog @yield('modal_class')">
    <div class="modal-content animated fadeIn">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">@yield('title')</h4>
        </div>
        <div class="modal-body">
            <div class="box box-info">
                <div class="box-body">@yield('body')</div>
            </div>
        </div>
        <div class="modal-footer">@yield('footer')</div>
    </div>
</div>
