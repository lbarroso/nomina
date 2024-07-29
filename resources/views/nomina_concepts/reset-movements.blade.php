@extends('layouts.main')

@section('content')
    <div class="card text-center">
	
	<div class="card-header">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2> Reseteo de Movimientos</h2>
			
		</div> 		
	</div>
		
        <div class="card-body">
		
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <p class="card-text">Esta opción permitirá borrar todos los movimientos de la semana actual y Aplicar cálculo de formulas otra vez</p>
            <form action="{{ route('reset.movements') }}" method="POST">
                @csrf

                <div class="form-row justify-content-center">
                    <div class="form-group col-md-4">
						<label for="semana">Semana actual</label>
						<select name="semana" id="semana" class="form-control" required>
							<option value="{{ $calendar->semana }}">SEMANA {{ $calendar->semana }}</option>
						</select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-trash-alt"></i> Resetear Movimientos
                </button>
            </form>
        </div>
    </div>
@endsection

