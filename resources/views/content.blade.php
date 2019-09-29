@extends('admin::index')

@section('content')

    <div class="row border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 id="title_name" data-title="{{ $header  }}">
                {{ $header }}
                <small>{{ $description }}</small>
            </h2>

            @if ($breadcrumb)
            <ol class="breadcrumb" style="margin-right: 30px;">
                <li><a no-pjax href="{{ admin_url('/') }}"><i class="fa fa-dashboard"></i> {{__('Home')}}</a></li>
                @foreach($breadcrumb as $item)
                    @if($loop->last)
                    <li class="active">
                        @if (array_has($item, 'icon'))
                            <i class="fa fa-{{ $item['icon'] }}"></i>
                        @endif
                        {{ $item['text'] }}
                    </li>
                    @else
                    <li>
                        <a no-pjax href="{{ admin_url(array_get($item, 'url')) }}">
                            @if (array_has($item, 'icon'))
                                <i class="fa fa-{{ $item['icon'] }}"></i>
                            @endif
                            {{ $item['text'] }}
                        </a>
                    </li>
                    @endif
                @endforeach
            </ol>
            @elseif(config('admin.enable_default_breadcrumb'))
            <ol class="breadcrumb" style="margin-right: 30px;">
                <li><a no-pjax href="{{ admin_url('/') }}"><i class="fa fa-dashboard"></i> {{__('Home')}}</a></li>
                @for($i = 2; $i <= count(Request::segments()); $i++)
                    <li>
                        {{ucfirst(Request::segment($i))}}
                    </li>
                @endfor
            </ol>
            @endif

        </div>
    </div>

    <section class="content">
        @include('admin::partials.alerts')
        @include('admin::partials.exception')
        @include('admin::partials.toastr')
        {!! $content !!}
    </section>

@endsection