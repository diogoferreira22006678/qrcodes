<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title??'Dasboard'}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href=' {{ asset('assets/css/dashboard.css')}} ' rel='stylesheet' />
    <link href=' {{ asset('assets/css/crud.css')}} ' rel='stylesheet' />
    <link rel="stylesheet" href="/lib/select2/select2.min.css"/>
    <link rel="stylesheet" href="/lib/datatables/datatables.min.css" />
    
    @yield('head')
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
</head>
<body>
    @if(!isset($simple) || $simple == false)
    @include('_components.navbar')
    @endif
    @yield('body')
    <script src="/lib/jquery/jquery.min.js"></script>
	<script src="/lib/datatables/datatables.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="/lib/select2/select2.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    
    <script> 
                 // define toast options
                toastr.options = {
                  "closeButton": true,
                  "debug": false,
                  "newestOnTop": false,
                  "progressBar": true,
                  "preventDuplicates": false,
                  "onclick": null,
                  "showDuration": "300",
                  "hideDuration": "1000",
                  "timeOut": "5000",
                  "extendedTimeOut": "1000",
                  "showEasing": "swing",
                  "hideEasing": "linear",
                  "showMethod": "fadeIn",
                  "hideMethod": "fadeOut",
                  "positionClass": "toast-bottom-right",
                  "css": {
                    "background-color": "green !important",
                    // make background image of toast null
                    "background-image": "none !important"
                  }
              };
    </script>
@yield('scripts')
</body>
</html>