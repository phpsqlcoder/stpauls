@extends('admin.layouts.report')

@section('pagecss')
    <!-- vendor css -->
    <link href="{{ asset('lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">

    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
    </style>
@endsection

@section('content')
<div style="margin:0px 40px 200px 40px;">
    <h4 class="mg-b-0 tx-spacing--1">Customer List</h4>
    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;word-break: break-all;">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Registration Date</th>
                <th>Mobile</th>
                <th>Phone</th>
                <th>Address 1</th>
                <th>Address 2</th>
                <th>Province</th>
                <th>City</th>
                <th>Country</th>
                <th>Postal</th>
            </tr>
        </thead>
        <tbody>  
            @forelse($rs as $r)
                <tr>
                    <td>{{$r->fullname}}</td>
                    <td>{{$r->email}}</td>
                    <td>{{($r->is_active == 1 ? 'Active' : 'Inactive')}}</td>
                    <td>{{$r->created_at}}</td>
                    <td>{{$r->mobile}}</td>
                    <td>{{$r->telno}}</td>
                    <td>
                        @if($r->country == 259)
                            {{$r->address}}
                        @else
                            {{$r->intl_address}}
                        @endif
                    </td>
                    <td>{{$r->barangay}}</td>
                    <td>
                        @if($r->province > 0)
                            {{$r->provinces->province}}
                        @endif
                    </td>
                    <td>
                        @if($r->city > 0)
                            {{$r->cities->city}}
                        @endif
                    </td>
                    <td>
                        @if($r->country > 0)
                            {{$r->countries->name}}
                        @endif
                    </td>
                    <td>{{$r->zipcode}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No report result.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/prismjs/prism.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>

@endsection

@section('customjs')
<script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.colVis.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            pageLength: 20,
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all'
                        }
                    }
                },
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {   
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all'
                        }
                    },
                    orientation : 'landscape',
                    pageSize : 'LEGAL'
                },
                'colvis'
            ],
            columnDefs: [ {
                targets: [],
                visible: false
            } ]
        } );
    } );
</script>
@endsection



