@foreach(BackendMenu::topMenu() as $item)
    @if(Admin::user()->visible($item['roles']) && (empty($item['permission']) ?: Admin::user()->can($item['permission'])))
    <li>
        <a href="{{ admin_base_path($item['uri']) }}" no-pjax>
            <i class="fa {{$item['icon']}}"></i> {{$item['title']}}
        </a>
    </li>
    @endif
@endforeach