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
    <link href="https://fonts.googleapis.com/css?family=Quicksand:500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/waves.min.css') }}" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/feather.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/pages.css') }}">
    <style type="text/css">
        .btn-bs5-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }
        .btn-bs5-primary:hover,
        .btn-bs5-primary:focus,
        .btn-bs5-primary:active {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            color: #fff;
        }
    </style>
</head>
<body themebg-pattern="theme1">
    <section class="login-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-8 offset-lg-2">
                    {{-- <form class="md-float-material form-material" method="POST" action="{{ route('checkLogin') }}"> --}}
                     <form class="md-float-material form-material theme-danger" method="POST" action="{{ route('checkLogin') }}">

                        @csrf
                        <div class="card mt-4">
                            <div class="container card-block">

                                <div class="row">
                                    <div class="col-md-12 col-lg-6">
                                        <img src="{{ asset('img/mspbs-logo.png') }}" width="100%" alt="SIGECON - MSPBS">
                                    </div>
                                    <div class="col-md-12 col-lg-6 p-t-30">
                                        <h3 class="text-center mt-2">SIGECON</h3>
                                        <h4 class="text-center text-mute">Sistema de Gestión de Contratos y Ordenes de Ejecución de Obras</h4>
                                        <hr class="mb-4">

                                        <div class="form-group">
                                            <input type="text" name="document" placeholder="Cédula" value="{{ old('document') }}" class="form-control @error('document') form-control-danger @enderror" autofocus required>
                                            @error('document')
                                                <div class="has-danger col-form-label">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input type="password" name="password" placeholder="Contraseña" class="form-control @error('password') form-control-danger @enderror" required>
                                            @error('password')
                                                <div class="has-danger col-form-label">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        @error('bad_credentials')
                                            <div class="has-danger col-form-label text-center">{{ $message }}</div>
                                        @enderror

                                        <div class="m-t-30">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-bs5-primary btn-md btn-block waves-effect text-center m-b-20">INGRESAR</button>
                                            </div>
                                        </div>

                                        <div class="text-center text-mute">
                                            SENASA<br>
                                            Ministerio de Salud Pública y Bienestar Social <br> {{ date('Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>

    <script src="{{ asset('template-admin/js/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/waves.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template-admin/js/jquery.slimscroll.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function() {

        @if (session()->has('error'))
            console.log('hola2');
            swal("Error!", "{{session()->get('error')}}", "error");
        @endif

      });
    </script>
</body>
</html>
