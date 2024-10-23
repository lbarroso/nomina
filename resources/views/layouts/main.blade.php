<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Nomina') }}</title>
	
	<!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">	

	<style>
		#scrollTopBtn {
			display: none; /* Oculto por defecto */
			position: fixed;
			bottom: 20px;
			right: 30px;
			z-index: 99;
			font-size: 18px;
			border: none;
			outline: none;
			background-color: #152C7D;
			color: white;
			cursor: pointer;
			padding: 15px;
			border-radius: 10px;
		}

		#scrollTopBtn:hover {
			background-color: #555; /* Color al pasar el cursor */
		}
		
		.navbar-custom {
			background-color: #152C7D; /* Azul */
		}		
		
		.table-custom {
			background-color: #5062A0; /* Azul Bootstrap */
			color: white; /* Texto blanco para contraste */
		}

		.table-custom th,
		.table-custom td {
			border-color: #5062A0; /* Color del borde un poco más oscuro */
		}
		
	</style>

	@yield('styles')

</head>

<body class="hold-transition sidebar-collapse layout-top-nav" style="width: 100%;">

<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-dark navbar-custom">
    <div class="container">
      <a href="{{ route('home') }}" class="navbar-brand">  
		<img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">      
        
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Catálogos</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
			
              <li><a href="{{ route('calendars.index') }}" class="dropdown-item"> <i class="fas fa-calendar-alt"></i> Calendario de pago </a></li>			  
			  <li><a href="{{ route('concepts.index') }}" class="dropdown-item">  <i class="fas fa-file-alt"></i> Conceptos de nómina </a></li>
              <li><a href="{{ route('firmas.create') }}" class="dropdown-item"> <i class="fas fa-pen-nib"></i> Firmas </a></li>
			  <li><a href="{{ route('salaries.index') }}" class="dropdown-item"> <i class="fas fa-briefcase"></i> Puestos / Salarios </a></li>
			  <li><a href="{{ route('employees.index') }}" class="dropdown-item"> <i class="fas fa-users"></i> Empleados </a></li>
			  <li><a href="{{ route('plantillas.index') }}" class="dropdown-item">   <i class="fas fa-file-excel"></i> Plantillas Excel  </a></li>
			  
            </ul>
          </li>
        </ul>
		
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Nómina Semanal</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              
			  <li><a href="{{ route('nomina_concepts.create') }}" class="dropdown-item"> <i class="fas fa-user-edit"></i> Movimientos por empleado </a></li>
			  <li><a href="{{ route('calcular.formulas') }}" class="dropdown-item">  <i class="fas fa-calculator"></i> Calular formulas </a></li>
			  <li><a href="{{ route('reset.form') }}" class="dropdown-item">  <i class="fas fa-eraser"></i> Resetear movimientos </a></li>
              <li><a href="{{ route('cierre.nomina') }}" class="dropdown-item"> <i class="fas fa-lock"></i> Cierre de nómina </a></li>
			  <li><a href="{{ route('delete.form') }}" class="dropdown-item"> <i class="fas fa-trash-alt"></i> Borrar nómina pagada </a></li>
			  
            </ul>
          </li>
        </ul>
		
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Reportes</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              
              <li><a href="{{ route('impresion.tabla') }}" class="dropdown-item"> <i class="fas fa-print"></i> Impresión de nómina</a></li>
              <li><a href="{{ route('poliza.tabla') }}" class="dropdown-item"> <i class="fas fa-file-invoice-dollar"></i> Polizas  </a></li>
              <li><a href="{{ route('recibo.tabla') }}" class="dropdown-item"> <i class="fas fa-receipt"></i> Recibos de empleados </a></li>
              <li><a href="{{ route('home') }}" class="dropdown-item"> <i class="fas fa-dollar-sign"></i> Integración de salarios </a></li>
              <li><a href="{{ route('acumulado.index') }}" class="dropdown-item"> <i class="fas fa-chart-line"></i> Acumulado de nómina </a></li>
              
            </ul>
          </li>
        </ul>

        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Nómina Especial</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
        
              <li><a href="{{ route('nominas.index') }}" class="dropdown-item">  <i class="fas fa-calendar-alt"></i>  Extraordinarias/Extemporáneas </a></li>
 
      			  
            </ul>
          </li>
        </ul>
		
      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">

		<!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-danger"> 4 </span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-header"> Usted tiene 4 avisos </span>
            <div class="dropdown-divider"></div>
            <a href="{{ route('notifications.index') }}" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> &nbsp;
              <span class="float-right text-muted text-sm"> ver todos los avisos </span>
            </a>

          </div>
        </li>
        
		<li class="dropdown user user-menu">
			<a href="#" class="nav-link" data-toggle="dropdown">
				Perfil
			</a>
			<ul class="dropdown-menu">
				<li class="user-footer">
					<div>
						<!-- Link to change password -->
						<a class="btn btn-default btn-flat" href="{{ route('password.change') }}">
							Cambiar Contraseña
						</a>
					</div>
					<br>
					<div>
						<a class="btn btn-default btn-flat" href="{{ route('logout') }}"
							onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							Cerrar Sesión
						</a>

						<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
							@csrf
						</form>                                
					</div>
				</li>
			</ul>
		</li>

      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
	<!-- Content Header (Page header) -->
    <div class="content-header" >
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"> {{ Auth::user()->uonom }} - <small>   {{  Auth::user()->almacen }} </small>  </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
 
              <li class="breadcrumb-item active"> {{ Auth::user()->regnom  }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content" >
      <div class="container">
        <div class="row">
		
          <div class="col-lg-12">
		  
			@yield('content')

          </div>
	
		</div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Versión 4.0 
    </div>
    <!-- Default to the left -->
    <strong> Sistema de Nómina Comunitaria </strong> 
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->

<script src="{{ asset('js/ajaxSetup.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/app/notify/notify.min.js') }}"></script>

<script>
// Mostrar el botón cuando el usuario se desplaza hacia el final de la página
document.addEventListener('scroll', function () {
    var scrollTopBtn = document.getElementById('scrollTopBtn');
    var scrollHeight = document.documentElement.scrollHeight;
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    var clientHeight = document.documentElement.clientHeight;

    if (scrollTop + clientHeight >= scrollHeight - 50) {
        scrollTopBtn.style.display = 'block';
    } else {
        scrollTopBtn.style.display = 'none';
    }
});

// Función para desplazar hacia arriba
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

@yield('scripts')

@yield('modal')
	
<!-- Botón Ir Arriba -->
<button id="scrollTopBtn" title="Ir Arriba" onclick="scrollToTop()"> <i class="fas fa-arrow-up"></i> </button>

	
</body>
</html>

