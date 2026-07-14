<!DOCTYPE html>
<html lang="es">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>SIGECON</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="SIGECON" />
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="Rodolfo González" />

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet"><link href="https://fonts.googleapis.com/css?family=Quicksand:500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/waves.min.css') }}" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/feather.css') }}">

    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/font-awesome.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/datatables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/buttons.datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datepicker/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/widget.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

    <div class="loader-bg">
        <div class="loader-bar"></div>
    </div>

    @include('include.header')

    @yield('content')

    @include('include.footer')

    <script src="{{ asset('template-admin/js/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/waves.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/jquery.slimscroll.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/pcoded.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/vertical-layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/jquery.datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/datatables.buttons.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/datatables.bootstrap4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/datatables.responsive.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/datepicker/bootstrap-datepicker.es.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/autonumeric.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/form-mask.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/inputmask.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/jquery.inputmask.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/script.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function() {

        @if (session()->has('success'))
            swal("Éxito!", "{{session()->get('success')}}", "success");
        @endif

        @if (session()->has('error'))
            swal("Error!", "{{session()->get('error')}}", "error");
        @endif

        // Para desplegar el menu activo
        $('.pcoded-submenu li.active').closest('.pcoded-submenu').css('display', 'block');
        $('.pcoded-submenu li.active').closest('.pcoded-hasmenu').addClass('pcoded-trigger');

      });
    </script>
    @stack('scripts')
</body>
</html>
