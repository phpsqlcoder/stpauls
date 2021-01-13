@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <style>
        .pagination { margin-top: 0px; }
    </style>
@endsection

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div id="col1" class="col-lg-3">
                    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.account-page-options')
                </div>
                <div id="col2" class="col-lg-9">
                    <nav class="rd-navbar">
                        <div class="rd-navbar-listing-toggle rd-navbar-static--hidden toggle-original" data-rd-navbar-toggle=".listing-filter-wrap"><span class="lnr lnr-list"></span> Options</div>
                    </nav>
                    <div class="article-content">
                        <h3 class="subpage-heading">{{ $page->name }}</h3>
                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="gap-20"></div>

                        <table id="wishlistTable" class="table table-md table-hover text-nowrap stripe" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col" width="20%">Product Code</th>
                                    <th scope="col" width="55%">Name</th>
                                    <th scope="col" width="15%">Amount</th>
                                    <th scope="col" width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wishlist as $list)
                                <tr id="row{{$list->id}}">
                                    <td>{{ $list->product_details->code }}</td>
                                    <td>{{ $list->product_details->name }}</td>
                                    <td>{{ number_format($list->product_details->discountedprice,2) }}</td>
                                    <td>
                                        <a target="_blank" href="{{ route('product.front.show',$list->product_details->slug)}}" title="View Product Details">
                                            <span class="lnr lnr-eye"></span>
                                        </a>
                                        <a href="#" title="Remove Product" id="cancelbtn" onclick="cancelOrder('{{$list->id}}')">
                                            <span class="lnr lnr-cross"></span>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center">No products found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('pagejs')
    <script src="{{ asset('theme/stpaul/plugins/datatables/datatables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>
        $(function () {
            var table = $('#wishlistTable').DataTable({
                "responsive": true,
                "scrollX": true,
                "scrollCollapse": true,
                "searching": false,
                "columnDefs": [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 }
                ],
                "order": [[0, 'desc']],
                "language": {
                    "paginate": {
                        "previous": "<i class='nr lnr-chevron-left'></i>",
                        "next": "<i class='nr lnr-chevron-right'></i>"
                    }
                },
                "pageLength": 5,
                "dom": 'rt<"text-left"i>p'
            });

            table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');

            $(window).resize(function () {
                table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');

                $("#wishlistTable tr.child").hover(function() {
                    $(this).find('td').css("background-color", "#dceefd");
                    $(this).prev().css("background-color", "#dceefd");
                },
                function() {
                    $(this).find('td').removeAttr("style");
                    $(this).prev().removeAttr("style");
                });
            });

            $('#wishlistTable').on( 'page.dt', function () {
                setInterval(function(){
                    table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
                }, 500);
            });

            $("#wishlistTable tr.child").hover(function() {
                $(this).find('td').css("background-color", "#dceefd");
                $(this).prev().css("background-color", "#dceefd");
            },
            function() {
                $(this).find('td').removeAttr("style");
                $(this).prev().removeAttr("style");
            });
        });
    </script>
@endsection

@section('customjs')
    <script>
        function cancelOrder(id){
            swal({
                title: 'Are you sure?',
                text: "Are you sure you want to remove this product from wishlist?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove  it!'            
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('wishlist.remove-product') }}",
                        data: { 
                                id : id,
                            },
                        success: function( response ) {
                            $('#row'+id).remove();
                            swal("Success!", "Product has been removed in the wishlist.", "success");
                            
                            
                        },
                        error: function( response ){
                            swal("Error!", "Failed to remove the product.", "danger"); 
                        }
                    });  
                } 
                else {                    
                    swal.close();                   
                }
            });
        }
    </script>
@endsection