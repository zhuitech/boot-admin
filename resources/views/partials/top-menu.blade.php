@if(Admin::user()->visible($item['roles']) && (empty($item['permission']) ?: Admin::user()->can($item['permission'])))
    <li class="{{$item['class']}}">
        <a href="{{ admin_base_path($item['uri']) }}" no-pjax>
            <i class="fa {{$item['icon']}}"></i>
            <span>{{$item['title']}}</span>
        </a>
    </li>
@endif