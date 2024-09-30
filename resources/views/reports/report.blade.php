@extends('layouts.main')

@section('content')
    <div class="card text-center">
	
	<div class="card-header">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2> formulario para generar el reporte </h2>
			
		</div> 		
	</div>
		
        <div class="card-body">

        <!-- resources/views/report.blade.php -->
        <form action="{{ route('export') }}" method="GET">
            @csrf
            <div class="form-group">
                <label for="year">Año:</label>
                <input type="number" class="form-control" id="year" name="year" required>
            </div>
            <div class="form-group">
                <label for="almcnt">Almacén:</label>
                <input type="number" class="form-control" id="almcnt" name="almcnt" required>
            </div>
            <div class="form-group">
                <label for="semana">Semana:</label>
                <input type="number" class="form-control" id="semana" name="semana" required>
            </div>
            <button type="submit" class="btn btn-primary">Exportar</button>
        </form>


        </div>
    </div>
@endsection

