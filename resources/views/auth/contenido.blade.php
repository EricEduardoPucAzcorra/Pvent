<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Sistema Ventas Laravel Vue Js- IncanatoIT">
  <meta name="author" content="Incanatoit.com">
  <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">

  <title>SoftVenta</title>
  <!-- Icons -->
  <link href="{{asset('vendors/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendors/css/simple-line-icons.min.css')}}" rel="stylesheet">
  <!-- Main styles for this application -->
  <link href="{{asset('vendors/css/style.css')}}" rel="stylesheet">

</head>

<body class="app flex-row align-items-center">
  <div class="container">
    @yield('login')
  </div>


  <!-- Bootstrap and necessary plugins -->
  <script src="{{asset('vendors/js/jquery.min.js')}}"></script>
  <script src="{{asset('vendors/js/popper.min.js')}}"></script>
  <script src="{{asset('vendors/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('vendors/js/pace.min.js')}}"></script>
  <!-- Plugins and scripts required by all views -->
  <script src="{{asset('vendors/js/Chart.min.js')}}"></script>
  <!-- GenesisUI main scripts -->
  <script src="{{asset('vendors/js/template.js')}}"></script>
  <script src="{{asset('js/sweetalert.js')}}"></script>
   <!-- vue js -->
  @stack('js')

</body>
</html>