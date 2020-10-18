<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="">
    <meta name="author">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Setting::info()->company_name }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage').'/icons/'.Setting::getFaviconLogo()->website_favicon }}">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('js/datatables/datatables.css') }}">
    
    @yield('pagecss') <!-- Add your own custom style and css-->
   
</head>

<body>

   
            <table width="100%">
                <tr width="30%">
                    <td><img src="{{  asset('storage/logos/'.Setting::getFaviconLogo()->company_logo) }}" alt="StPaul" width="200" /></td>
                    <td width="40%" style="font-size:25px;" class="text-center">
                        <b>ST PAULS Catholic Online Bookstore</b>
                    </td>
                    <td width="30%">&nbsp;</td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="40%" style="font-size:15px;" class="text-center">7708 St. Paul Road, San Antonio Village, 1203 Makati City, Philippines</td>
                    <td width="30%">&nbsp;</td>
                </tr>
            </table>
        

            @yield('content')


       

   

    

    <script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/datatables/JSZip-2.5.0/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/datatables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.print.min.js') }}"></script>



    <!--Put your external scripts here -->
    @yield('pagejs')




    @yield('customjs')

</body>
</html>
