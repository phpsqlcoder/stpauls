@php
    $page = $item->page;
@endphp
@if (!empty($page) && $item->is_page_type() && $page->is_published())
    <li @if(url()->current() == $page->get_url() || ($page->id == 1 && url()->current() == \URL::to('/'))) class="active" @endif>
        <a href="{{ $page->get_url() }}">
            @if (!empty($page->label))
                {{ $page->label }}
            @else
                {{ $page->name }}
            @endif
        </a>
        @if ($item->has_sub_menus())
            <ul class="rd-navbar-dropdown">
                @foreach ($item->sub_pages_by_order() as $subItem)
                    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.menu-item', ['item' => $subItem])
                @endforeach
            </ul>
        @endif
    </li>
@elseif ($item->is_external_type())
    <li>
        <a href="{{ $item->uri }}" target="{{ $item->target }}">{{ $item->label }}</a>
        @if ($item->has_sub_menus())
            <ul class="rd-navbar-dropdown">
                @foreach ($item->sub_pages_by_order() as $subItem)
                    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.menu-item', ['item' => $subItem])
                @endforeach
            </ul>
        @endif
    </li>
@endif
