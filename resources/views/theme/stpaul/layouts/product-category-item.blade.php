<ul class="rd-navbar-dropdown">
    @foreach($subcategories as $subcategory)
        <li>
            <a href="">{{$subcategory->name}}</a>
            @if(count($subcategory->child_categories))
                @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.product-category-item',['subcategories' => $subcategory->child_categories])
            @endif
        </li>
    @endforeach
</ul>
