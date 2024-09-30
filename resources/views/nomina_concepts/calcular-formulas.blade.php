@extends('layouts.main')

@section('content')

<div class="card">

	<div class="card-header">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2> Aplicar cálculo de formulas</h2>
		</div> 		
	</div>
	
	<div class="card-body">
	
		@if(session('success'))
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		@endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif		

		<form action="{{ route('calcular.nomina') }}" method="POST">
			@csrf

			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="tabulador">Tabulador</label>
					<select name="tabulador" id="tabulador" class="form-control" required>
						<option value="actual"> APLICAR CALCULO TABULADOR ACTUAL</option>
						<option value="anterior">APLICAR CALCULO TABULADOR ANTERIOR</option>
					</select>
				</div>

				<div class="form-group col-md-6">
					<label for="semana">Semana actual</label>
					<select name="semana" id="semana" class="form-control" required>
						<option value="{{ $calendar->semana }}">SEMANA {{ $calendar->semana }}</option>
					</select>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="fecha_inicio">Fecha de Inicio</label>
					<input type="text" id="fecha_inicio" class="form-control" value="{{ \Carbon\Carbon::parse($calendar->fechaInicio)->format('d-m-Y') }}" disabled>
				</div>

				<div class="form-group col-md-4">
					<label for="fecha_fin">Fecha de Fin</label>
					<input type="text" id="fecha_fin" class="form-control" value="{{ \Carbon\Carbon::parse($calendar->fechaFin)->format('d-m-Y') }}" disabled>
				</div>

				<div class="form-group col-md-4">
					<label for="dias_pagados">Días Pagados</label>
					<input type="text" id="dias_pagados" class="form-control" value="{{ $calendar->diasPagados }}" disabled>
				</div>
			</div>

			<div class="form-check mb-3">
				<input class="form-check-input" type="checkbox" id="uma" name="uma" value="1" checked>
				<label class="form-check-label" for="uma">
					Valor de la UMA actual
				</label>
			</div>

			<button type="submit" class="btn btn-success" id="calculoButton">
				<i class="fas fa-calculator"></i> Aplicar Cálculo
			</button>
			
		</form>
	</div>
	
	<div class="card-footer">
		<div class="form-group col-md-12">
			<div id="alerta"></div>
		</div>
	</div>
			
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const montoGroup = document.getElementById('alerta');
		const count = {{ $count }};
        const calculoButton = document.getElementById('calculoButton');

        if (count > 0) {
            calculoButton.disabled = true;
			montoGroup.innerHTML = '<div class="alert alert-warning alert-dismissible"> <h5><i class="icon fas fa-exclamation-triangle"></i>  El cálculo de fórmulas para esta semana ya ha sido aplicado. </h5> <p> 1. Para recalcular las fórmulas, intente realizar el reseteo de movimientos. <a class="link" href="{{ route('reset.form') }}">resetear movimientos</a> <br> 2. Para ver los movimientos calculados, vaya a este enlace. <a class="link" href="{{ route('impresion.tabla') }}">impresión listado de nómina </a> </p>  </div>';
	
        }
    });
</script>

@endsection

<style>

    .card-body {
        background-color: #f8f9fa;
    }
    .form-check-label {
        margin-left: 1.25rem;
    }
</style>