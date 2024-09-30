@extends('layouts.main')

@section('content')

	<div class="card">
		
		<div class="card-header">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h2>Información para integreción de salarios</h2>		
				{{ $employee->expediente }} {{ $employee->nombre }} {{ $employee->paterno }} {{ $employee->materno;}}
			</div> 		
		</div>
		
		<div class="card-body">
		
			<form action="{{ route('variables.update') }}" method="POST">
				
				@csrf

				@php
					$salarioDiario = $employee->salarioDiario($employee->salary->tab_vig);
				@endphp
				@if($employee->salary->puesto == "VELADOR")
					@php
						$pagoDiaDomingo = $employee->pagoDiaDomingo($salarioDiario);
						$primaDominical = $employee->primaDominical($pagoDiaDomingo);
						$pagoFijoVelador = $pagoDiaDomingo + $primaDominical;
					@endphp
				@else
					@php
						$pagoDiaDomingo = 0;
						$primaDominical = 0;
						$pagoFijoVelador = 0;						
					@endphp									
				@endif				

				<!-- Sección para mostrar elementos fijos -->
				<h4>Elementos Fijos</h4>
				<div class="form-group row">
					<label for="salarioDiario" class="col-sm-4 col-form-label">Salario Diario:  </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="salarioDiario" value="{{ number_format($salarioDiario, 2) }}" readonly>
					</div>
				</div>				
								
				<div class="form-group row">
					<label for="aguinaldoDiario" class="col-sm-4 col-form-label">Aguinaldo Diario: <small class="text-muted">(Salario diario x 40 / {{ $diasDelAnioActual }})</small> </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="aguinaldoDiario" value="{{ number_format($employee->aguinaldoDiario($salarioDiario), 2) }}" readonly>
					</div>
				</div>
				
				<div class="form-group row">
					<label for="primaVacacional" class="col-sm-4 col-form-label">Prima Vacacional: <small class="text-muted">(1 año de trabajo) (6x1x25%/365)</small> </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="primaVacacional" value="{{ number_format($employee->primaVacacional($employee->vacaciones, $salarioDiario), 2) }}" readonly>
					</div>
				</div>
				
			

				<!-- Sección para actualizar atributos de Employee -->
				<h4>Elementos variables</h4>
				
				<div class="form-group row">
					<label for="pagoDiaDomingo" class="col-sm-4 col-form-label">Pago Día Domingo: <small class="text-muted">(salario diario * 2 / 7) </small>  </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="pagoDiaDomingo" id="pagoDiaDomingo" value="{{ old('valeMensual', $employee->pagoDiaDomingo) }}" >
					</div>
				</div>				
				
				<div class="form-group row">
					<label for="primaDominical" class="col-sm-4 col-form-label">Prima Dominical: <small class="text-muted"> (pago día domingo * 0.25) </small> </label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="primaDominical" id="primaDominical" value="{{ old('valeMensual', $employee->primaDominical) }}" >
					</div>
				</div>		

				<div class="form-group row">
					<label for="valeMensual" class="col-sm-4 col-form-label">Vale Mensual:</label>
					<div class="col-sm-8">
						<input type="number" step="any" class="form-control @error('valeMensual') is-invalid @enderror" name="valeMensual" id="valeMensual" value="{{ old('valeMensual', $employee->valeMensual) }}">
						@error('valeMensual')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>					

				
				<div class="form-group row">
					<label for="valeNavideno" class="col-sm-4 col-form-label">Vale Navideño:</label>
					<div class="col-sm-8">
						<input type="number" step="any" class="form-control @error('valeNavideno') is-invalid @enderror" name="valeNavideno" id="valeNavideno" value="{{ old('valeNavideno', $employee->valeNavideno) }}">
						@error('valeNavideno')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>				
	
				
				<div class="form-group row">
					<label for="variableOtro" class="col-sm-4 col-form-label">Variable Otro:</label>
					<div class="col-sm-8">
						<input type="number" step="any" class="form-control @error('variableOtro') is-invalid @enderror" name="variableOtro" id="variableOtro" value="{{ old('variableOtro', $employee->variableOtro) }}">
						@error('variableOtro')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>
				

				<!-- Sección para actualizar atributos de Calendar -->
				<h4>Actualizar Días Bimestre</h4>

				<div class="form-group row">
					<label for="bimestre" class="col-sm-4 col-form-label"> Días: <small class="text-muted">(mes {{ $calendar->nombre_mes }} )</small>  </label>
					<div class="col-sm-8">
						<input type="number" step="any" class="form-control @error('bimestre') is-invalid @enderror" name="bimestre" id="bimestre" value="{{ old('bimestre', $calendar->bimestre) }}">
						@error('bimestre')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>				
				
				<a href="{{ route('home') }}" class="btn btn-default"> Cancelar </a>
				<button type="submit" class="btn btn-success"> <i class="fas fa-save"></i> Actualizar </button>				
				<input type="hidden" name="mes" id="mes" value="{{ $calendar->mes }}">
				<input type="hidden" name="id" id="id" value="{{ $employee->id }}">
				<input type="hidden" name="almcnt" id="almcnt" value="{{ $employee->almcnt }}">
			</form>

		</div>
	  
	</div>

@endsection
