<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js- IncanatoIT">
    <meta name="author" content="Incanatoit.com">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">
    <link rel="shortcut icon" href="vendors/img/favicon.png">
    <title>SoftVenta</title>
    <!-- Icons -->
    <link href="{{asset('vendors/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/css/simple-line-icons.min.css')}}" rel="stylesheet">
    <!-- Main styles for this application -->
    <link href="{{asset('vendors/css/style.css')}}" rel="stylesheet">

    
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="proyecto">
        <header class="app-header navbar">
            <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
            <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
            <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="nav navbar-nav d-md-down-none">
                <li v-on:click="menu=0" class="nav-item px-3">
                    <a class="nav-link" href="#">Escritorio</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">Configuraciones</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item d-md-down-none">
                    <a class="nav-link" href="#" data-toggle="dropdown">
                        <i class="icon-bell"></i>
                        <span class="badge badge-pill badge-danger">5</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>Notificaciones</strong>
                        </div>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-envelope-o"></i> Ingresos
                            <span class="badge badge-success">3</span>
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-tasks"></i> Ventas
                            <span class="badge badge-danger">2</span>
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="vendors/img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        <span class="d-md-down-none">{{Auth::user()->usuario}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>Cuenta</strong>
                        </div>
                        {{-- <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Perfil</a> --}}
                        <a class="dropdown-item" href="{{route('salir')}}" 
                        onclick="event.preventDefault(); document.getElementById('form-salir').submit();">
                        <i class="fa fa-lock"></i> Cerrar sesión</a>

                        <form id="form-salir" action="{{route('salir')}}" method="post" style="display: none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </header>


        <div class="app-body">

            {{-- valido usuario  si esta autenticado--}}
            @if (Auth::check())
                {{-- autenticado como Administrador --}}
                @if(Auth::user()->id_rol==1)

                @include('layout.menuAdmin')
                 {{-- autenticado como Vendedor--}}
                @elseif(Auth::user()->id_rol==2)

                @include('layout.menuVendedor')
                 {{-- autenticado como Almacenero --}}
                @elseif(Auth::user()->id_rol==3)
                
                @include('layout.menuAlmacenero')
                
                @else

                @endif

            @endif

            <!-- Contenido Principal -->
            @yield('contenido')

        
        </div>

    </div>

    <footer class="app-footer">
        <span><a href="http://www.incanatoit.com/">IncanatoIT</a> &copy; 2017</span>
        <span class="ml-auto">Desarrollado por <a href="#">Eric Azcorra</a></span>
    </footer>

    <script src="{{asset('js/proyecto.js')}}"></script>
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