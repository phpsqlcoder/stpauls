<ul class="rd-navbar-nav-l">
    @php
        $menu = \App\Menu::where('is_active', 1)->first();
    @endphp
    @foreach ($menu->parent_navigation() as $item)
        @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.menu-item', ['item' => $item])
    @endforeach

    <li @if (\Route::current()->getName() == 'front.branches') class="active" @endif>
        <a href="{{ route('front.branches') }}">Branches</a>
    </li>

    <li @if (\Route::current()->getName() == 'front.branches') class="active" @endif>
        <a href="{{ route('front.request-a-title') }}">REQUEST A TITLE</a>
    </li>
</ul>