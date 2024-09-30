@extends('layouts.main')

@section('content')

	<div class="card">
		
		<div class="card-header">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h2>Empleados</h2>
				<a href="{{ route('employee.create') }}" class="btn btn-primary"> <i class="fas fa-user-plus"></i> Nuevo Empleado</a>
			</div> 		
		</div>
		
		<div class="card-body">
		
			@if(session('success'))
				<div class="alert alert-success">
					<i class="icon fas fa-check"></i> {{ session('success') }}
				</div>
			@endif
			@if($errors->has('error'))
				<div class="alert alert-danger">
					{{ $errors->first('error') }}
				</div>
			@endif

			<div class="table-responsive">  
			
				<table class="table table-striped table-bordered table-hover text-uppercase" id="employee" class="display" style="width:100%; font-size:11pt">
					
					<thead class="table-custom">
						<tr>
						<th></th>
						<th>Alta/Baja</th>		
						<th>Nombre(s)</th>
						<th>A. Paterno</th>
						<th>A. Materno</th>
						<th>CURP</th>
						<th>RFC</th>
						<th>No Empleado</th>
						<th>Puesto</th>														
						<th>Sueldo Diario</th>
						<th>NSS</th>	
						</tr>
					</thead> 
					
					<!--empleyees.js-->
					
				</table>
				
			</div>

			<a href="{{ route('employees.plantillapdf') }}" target="_blank" class="link">descargar plantilla PDF</a>
		</div>
	  
	</div>

@endsection

@section('styles')
	<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('scripts')
	<script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

	<!--rutas web apiResource:employees-->
	<script>
		var indexUrl = '{{ route("employees.index") }}';
		var storeUrl = '{{ route("employees.store") }}';
		var updateUrl = '{{ route("employees.update",["employee" => 0]) }}';
		var showUrl = '{{ route("employees.show",["employee" => 0]) }}';
		var urlSalaries = '{{ route("employees.salaries") }}';
	</script>

	<!--contiene el metodo buscar data tables-->
	<script src="{{ asset('js/admin/employee.js') }}"></script>
@endsection

@section('modal')
    @include('employees.modalEmployee')
@endsection