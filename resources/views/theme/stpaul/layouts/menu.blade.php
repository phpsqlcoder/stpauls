<ul class="rd-navbar-nav-l">
    @php
        $menu = \App\Menu::where('is_active', 1)->first();
    @endphp
    @foreach ($menu->parent_navigation() as $item)
        @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.menu-item', ['item' => $item])
    @endforeach
</ul>