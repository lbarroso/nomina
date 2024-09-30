@extends('layouts.main')

  <!-- DataTables -->
@section('styles')
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

	<div class="card">
		
		<div class="card-header">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h2>Integración de Salarios</h2>
				
			</div> 		
		</div>
		
		<div class="card-body">
		
			@if(session('success'))
				<div class="alert alert-success">
					{{ session('success') }}
				</div>
			@endif		
			
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif			
		
			<div class="row">

				<div class="table-responsive">  
				
					<table class="table table-striped table-bordered table-hover" id="example1" style="width:100%; font-size:11pt">
					
						 <thead class="table-custom">
							<tr>
								<th>&nbsp;</th>
								<th>SDI</th>								
								<th>Nombre del Empleado</th>
								<th>CURP</th>
								<th>RFC</th>
								<th>No Empleado</th>
								<th>NSS</th>
								<th>Fecha de Ingreso</th>
								<th>Antigüedad</th>
								<th>Días de Vacaciones</th>
								<th>Salario Base</th>
								<th>Sueldo Diario</th>
								<th>Puesto</th>
								<th>Aguinaldo Diario </th>
								<th>Prima Vacacional</th>
								<th>Salario Fijo </th>
								<th>Pago día Domingo</th>
								<th>Prima Dominical</th>
								<th>SDI Fijo </th>
								<th>Vale Mensual</th>
								<th>Vale Navideño</th>
								<th>Otros</th>
								<th>Parte Variable</th>
	
								<th> </th>
							</tr>
						</thead>
						
						<tbody>
							@foreach($employees as $employee)
								@php
									$salarioDiario = $employee->salarioDiario($employee->salary->tab_vig);	
									$salarioFijo = $salarioDiario + $employee->primaVacacional($employee->vacaciones, $salarioDiario) + $employee->aguinaldoDiario($salarioDiario);
									$parteVariable = $employee->parteVariable($calendar->bimestre);
								@endphp								
								
								@php
									$pagoDiaDomingo = $employee->pagoDiaDomingo;
									$primaDominical = $employee->primaDominical;
									$pagoFijoVelador = $pagoDiaDomingo + $primaDominical;
								@endphp
								
								@php
									$salarioDiarioIntegrado = $pagoFijoVelador + $salarioFijo;
									$SDI = $salarioFijo + $parteVariable + $pagoFijoVelador;
								@endphp
								
								<tr>
									<td></td>
									<td>{{ number_format($SDI,2) }}</td>									
									<td>{{ $employee->nombre }} {{ $employee->paterno }} {{ $employee->materno }}</td>
									<td>{{ $employee->curp }}</td>
									<td>{{ $employee->rfc }}</td>
									<td>{{ $employee->expediente }}</td>
									<td>{{ $employee->nss }}</td>
									<td>{{ \Carbon\Carbon::parse($employee->fechaIngreso)->format('d-m-Y') }}</td>
									<td>{{ floor($employee->antiguedad) }} años</td>
									<td>{{ $employee->vacaciones }} días</td>
									<td>{{ number_format($employee->salary->tab_vig,2) }}</td>
									<td>{{ number_format($salarioDiario, 2) }}</td>
									<td>{{ $employee->salary->puesto }}</td>
									<td>{{ number_format($employee->aguinaldoDiario($salarioDiario), 2) }}</td>
									<td>{{ number_format($employee->primaVacacional($employee->vacaciones, $salarioDiario), 2) }}</td>	
									<td>{{ number_format($salarioFijo,2) }}</td>
									<td>{{ number_format($pagoDiaDomingo,2) }}</td>
									<td>{{ number_format($primaDominical,2) }}</td>
									<td>{{ number_format($salarioDiarioIntegrado,2) }}</td>
									<td>{{ number_format($employee->valeMensual ,2) }}</td>
									<td>{{ number_format($employee->valeNavideno ,2) }}</td>
									<td>{{ number_format($employee->variableOtro ,2) }}</td>
									<td>{{ number_format($parteVariable,2) }}</td>						
									<td> 
										<a href="{{ route('employee.edit', ['id' => $employee->id]) }}" title="modificar" class="btn btn-warning btn-sm mr-1"> 
											<i class="fas fa-edit"></i> modificar 
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
						
					</table>
	
				</div>
		  
			</div>

		</div>
	  
	</div>

@endsection


<!-- DataTables  & Plugins -->
@section('scripts')
<script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Page specific script -->
<script>
  $(function () {

    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel" ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });
</script>

@endsection