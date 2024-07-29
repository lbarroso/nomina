@extends('layouts.main')

@section('content')

	<div class="card">
		
		<div class="card-header">
			<div>
				<h2>Aplicar Baja de Empleado</h2> 
				{{ $employee->nombre }}  {{ $employee->paterno }} {{ $employee->materno }} 
			</div> 		
		</div>
		
		<div class="card-body">
		
			<div class="row">
			
				<form action="{{ route('employees.terminate') }}" method="POST" id="terminateForm">
					@csrf
					<div class="form-group">
						<label for="fechaTermino">Fecha de Término(*):</label>
						<input type="date" name="fechaTermino" id="fechaTermino" class="form-control" required>
						@error('fechaTermino')
							<span class="text-danger">{{ $message }}</span>
						@enderror
					</div>
					
					<input type="hidden" name="id" id="id" value="{{ $employee->id }}">
					<a href="{{ route('employees.index') }}" class="btn btn-default" >Cancelar</a>
					<button type="submit" class="btn btn-danger">Confirmar Baja de Empleado</button>
				</form>

			</div>

		</div>
	  
	</div>

@endsection

@section('scripts')
	<script>
		$(document).ready(function() {
			$('#terminateForm').validate({
				rules: {
					fechaTermino: {
						required: true,
						date: true
					}
				},
				messages: {
					fechaTermino: {
						required: "La fecha de término es obligatoria.",
						date: "Por favor, ingresa una fecha válida."
					}
				},
				errorClass: 'text-danger'
			});
		});
	</script>
@endsection