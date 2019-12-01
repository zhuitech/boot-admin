<div class="box laravel-admin">
    @if(isset($title))
        <div class="box-header with-border">
            <h3 class="box-title"> {{ $title }}</h3>
        </div>
    @endif

    <div class="box-header with-border">
        <div class="pull-right">
            {!! $grid->renderExportButton() !!}
            {!! $grid->renderCreateButton() !!}
        </div>
        <span>
            {!! $grid->renderHeaderTools() !!}
        </span>
    </div>

    {!! $grid->renderFilter() !!}

    <!-- /.box-header -->
    <div class="box-body">
        <ul class="mailbox-attachments clearfix">
            @section('rows')
            @foreach($grid->rows() as $row)
                <li>
                    <span class="mailbox-attachment-icon has-img">
                        {!! $row->column('image') !!}
                    </span>
                    <div class="mailbox-attachment-info" style="text-align: center">
                        <a href="#" class="mailbox-attachment-name">
                            {!! $row->column('title') !!}
                        </a>
                        <br />
                        @foreach($grid->visibleColumns() as $column)
                            @if(!in_array($column->getName(), ['image', 'title', '__row_selector__', '__actions__']))
                            {!! $column->getLabel() . '：' . $row->column($column->getName()) !!}
                            <br />
                            @endif
                        @endforeach
                        {!! $row->column('__actions__') !!}
                        <span class="mailbox-attachment-size">
                            <span class="pull-right">
                            {!! $row->column('__row_selector__') !!}
                            </span>
                        </span>
                    </div>
                </li>
            @endforeach
            @show
        </ul>

    </div>
    <div class="box-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>