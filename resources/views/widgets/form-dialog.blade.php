<div class="modal-dialog modal-lg">
    <div class="modal-content animated fadeIn">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{!! $title ?? '' !!}</h4>
        </div>
        <div class="modal-body">{!! $form !!}</div>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">取消</button>
            <button type="submit" class="btn btn-primary" data-toggle="form-submit" data-target="#modal form">提交</button>
        </div>
    </div>
</div>
<style>
    .modal .box-header, .modal .box-footer { display: none; }
    .modal .modal-footer { border-top: 0; }
    .inmodal .modal-header { padding: 15px; }
</style>
<script>
    $('#modal form').ajaxForm({
        success: function (result) {
            if (!result.status) {
                swal("操作失败!", result.message, "error")
            } else {
                swal({
                    title: "操作成功！",
                    text: "",
                    type: "success"
                }).then(function() {
                    $('#modal').modal('hide');
                    location = decodeURIComponent('{!! $redirect_url ?? '' !!}');
                });
            }
        },
        error: function (data) {
            var result = jQuery.parseJSON(data.responseText);
            swal("操作失败!", result.message, "error");
        }
    });
</script>
{!! Admin::script() !!}