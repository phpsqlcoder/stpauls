@foreach($subcategories as $subcategory)
    <tr>
        <th class="text-right">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input cb" id="cb{{ $subcategory->id }}">
                <label class="custom-control-label" for="cb{{ $subcategory->id }}"></label>
            </div>
        </th>
        <td>
            <strong @if($subcategory->trashed()) style="text-decoration:line-through;" @endif>{{ $subcategory->name }}</strong>
        </td>
        <td class="text-center">{{ $subcategory->totalsub }}</td>
        <td class="text-center">{{ $subcategory->totalproducts }}</td>
        <td>{{ $subcategory->status }}</td>
        <td>{{ Setting::date_for_listing($subcategory->updated_at) }}</td>
        <td>
            @if($subcategory->trashed())
                @if (auth()->user()->has_access_to_route('product.category.restore'))
                    <nav class="nav table-options">
                        <a class="nav-link" href="{{route('product.category.restore', $subcategory->id)}}" title="Restore this category"><i data-feather="rotate-ccw"></i></a>
                    </nav>
                @endif
            @else
                <nav class="nav table-options float-right">

                    @if($subcategory->totalsub > 0)
                    <a href="javascript:;" class="nav-link" data-toggle="collapse" data-target="#subsubcat_{{$subcategory->id}}" class="accordion-toggle" title="View Sub-categories"><i data-feather="list"></i></a>
                    @endif

                    <a class="nav-link" target="_blank" href="{{route('product.index.advance-search')}}?name=&category_id={{$subcategory->id}}&user_id=&short_description=&description=&status=&price1=&price2=&updated_at1=&updated_at2=" title="View Products"><i data-feather="eye"></i></a>

                    @if (auth()->user()->has_access_to_route('product-categories.edit'))
                        <a class="nav-link" href="{{ route('product-categories.edit',$subcategory->id) }}" title="Edit Category"><i data-feather="edit"></i></a>
                    @endif

                    @if (auth()->user()->has_access_to_route('product.category.single.delete'))
                        @if($subcategory->totalsub == 0 && $subcategory->totalproducts == 0)
                        <a class="nav-link" href="javascript:void(0)" onclick="delete_one_category('{{$subcategory->id}}','{{$subcategory->name}}')" title="Delete Category"><i data-feather="trash"></i></a>
                        @else
                        <a class="nav-link" href="javascript:void(0)" onclick="$('#prompt-not-delete').modal('show');"><i data-feather="trash"></i></a>
                        @endif
                    @endif

                    @if (auth()->user()->has_access_to_route('product.category.change-status'))
                        <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="settings"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if($subcategory->status == 'PUBLISHED')
                                <a class="dropdown-item" href="{{route('product.category.change-status',[$subcategory->id,'PRIVATE'])}}" > Private</a>
                            @else
                                <a class="dropdown-item" href="{{route('product.category.change-status',[$subcategory->id,'PUBLISHED'])}}"> Publish</a>
                            @endif
                        </div>
                    @endif
                </nav>
            @endif
        </td>
    </tr>
    @if(count($subcategory->child_categories))
    <tr>
        <td colspan="8" class="hiddenRow" style="padding: 0px;">
            <div class=" collapse" id="subsubcat_{{$subcategory->id}}">
                <table class="table table-sm table-hover mg-b-20">
                    <thead>
                        <tr>
                            <th width="8%"></th>
                            <th width="27%" class="text-uppercase"><h5>SUB-CATEGORY : {{ $subcategory->name }}</h5></th>
                            <th width="10%"></th>
                            <th width="10%"></th>
                            <th width="10%"></th>
                            <th width="15%"></th>
                            <th width="15%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('admin.products.subcategories',['subcategories' => $subcategory->child_categories])
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
    @endif
@endforeach


    