@extends('layouts.main')

@section('content')

<div class="card">

	<div class="card-header">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2> Acumulado de nómina </h2>							
		</div> 
	</div>

	<div class="card-body">

		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif	
			
		<form id="acumuladoForm" method="POST" action="{{ route('acumulado.post') }}">
			@csrf

			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="year">Ejercicio</label>
					<select name="year" id="year" class="form-control" required>
						<option value="{{ $calendar->year }}"> {{ $calendar->year }} </option>						
					</select>
				</div>
			</div>
			
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="semana_inicio">Semana Inicial</label>
					<select class="form-control" name="semanaInicio">
						@for ($i = 1; $i <= $calendar->semana; $i++)
							<option value="{{ $i }}" {{ $i == $calendar->semana ? 'selected' : '' }}>Semana {{ $i }}</option>
						@endfor					
					</select>
				</div>

				<div class="form-group col-md-6">
					<label for="semana_final">Semana Final</label>
					<select class="form-control" name="semanaFin">
						@for ($i = 1; $i <= $calendar->semana; $i++)
							<option value="{{ $i }}" {{ $i == $calendar->semana ? 'selected' : '' }}>Semana {{ $i }}</option>
						@endfor					
					</select>
				</div>			
			</div>
			
			<div class="form-row">
				<button type="submit" class="btn btn-primary">
					<i class="fas fa-search"></i> Consultar
				</button>
			</div>
			
		</form>

	</div>
	
</div>

@endsection