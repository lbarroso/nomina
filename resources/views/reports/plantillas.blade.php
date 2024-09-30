@extends('layouts.main')

@section('content')

<div class="card">

	<div class="card-header">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2> Plantillas Excel para Sinube</h2>
		</div> 		
	</div>
	
	<div class="card-body">

		<form id="plantillaForm" method="POST" action="{{ route('plantilla.nomina.store') }}">
			@csrf

			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="year">Ejercicio</label>
					<select name="year" id="year" class="form-control" required>
						<option value="{{ $year }}"> {{ $year }} </option>						
					</select>
				</div>
			</div>
			
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="semana">Semana cerrada</label>
					<select class="form-control" name="semana">
						<option value=""> SELECCIONAR SEMANA</option>
						@for ($i = 1; $i <= $ultimaSemanaCalendario; $i++)
							<option value="{{ $i }}">Semana {{ $i }}</option>
						@endfor					
					</select>
				</div>			
			</div>

			<div class="form-row">			
				<div class="form-group col-md-6">
					<label for="plantilla">Plantilla</label>
					<select name="plantilla" id="plantilla" class="form-control" required>
						<option value="nomina">Plantilla Nómina</option>
						<option value="employee">Plantilla Empleados</option>
					</select>
				</div>			
			</div>
			
			<div class="form-row">
				<button type="submit" class="btn btn-primary">
					<i class="fas fa-file-excel"></i> Generar plantilla
				</button>
			</div>
			
		</form>
		
	</div>
	
	<div class="card-footer">
		<div class="form-group col-md-12">
			@if(session('nomina'))
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h5> <i class="fas fa-download"></i> Plantilla generada exitosamente</h5>				
				<a class="btn btn-primary" href="{{ route('plantilla.nomina.download',['semana' => session('nomina')]) }}">   Descargar plantilla nómina semana {{ session('nomina') }} </a>
			</div>
			@endif
			@if(session('employee'))
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h5> <i class="fas fa-download"></i> Plantilla generada exitosamente</h5>				
				<a class="btn btn-primary" href="{{ route('plantilla.employee.download',['semana' => session('employee')]) }}">   Descargar plantilla empleados semana {{ session('employee') }} </a>
			</div>
			@endif			
		</div>
	</div>
			
</div>

<script>
    document.getElementById('plantilla').addEventListener('change', function () {
        var form = document.getElementById('plantillaForm');
        var selectedValue = this.value;

        if (selectedValue === 'nomina') {
            form.action = '{{ route('plantilla.nomina.store') }}';
        } else if (selectedValue === 'employee') {
            form.action = '{{ route('plantilla.employee.store') }}';
        }
    });
</script>

@endsection
