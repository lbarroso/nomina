@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Movimientos por empleado</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('nomina_concepts.store') }}" method="POST" id="createNominaConcept">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="semana">Semana actual</label>
                        <select name="semana" id="semana" class="form-control" required>
                            <option value="{{ $calendar->semana }}">SEMANA {{ $calendar->semana }}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="employee_id">Empleado</label>
                        <select name="employee_id" id="employee_id" class="form-control" required>
                            <option value="">SELECCIONAR UNA OPCIÓN</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->nombre }} {{ $employee->paterno }} {{ $employee->materno }} : {{ $employee->salary->puesto }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="concept_id">Concepto</label>
                        <select name="concept_id" id="concept_id" class="form-control" required>
                            <option value="">SELECCIONAR UNA OPCIÓN</option>
                            @foreach($concepts as $concept)
                                <option value="{{ $concept->id }}"> {{ strtoupper($concept->descripcion) }} : {{ $concept->id }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6" id="monto-group">
                        <label for="monto">Monto</label>
                        <input type="number" step="0.01" name="monto" id="monto" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="calculoButton">Agregar Movimiento</button>
            </form>

            <hr>

			<h3 class="text-muted">Movimientos Agregados</h3>
			
            @if($movements->isEmpty())
                <div class="alert alert-info">No hay movimientos agregados.</div>
            @else			
					
				<table class="table table-bordered text-muted">
					<thead>
						<tr>
							<th>Empleado</th>
							<th>Concepto</th>
							<th>Monto</th>
							<th>Fecha</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody class="text-uppercase">
						@foreach($movements as $movement)
							<tr>
								<td>{{ $movement->nombre }} {{ $movement->paterno }} {{ $movement->materno }}</td>
								<td>{{ $movement->descripcion }}</td>
								<td>{{ $movement->monto }}</td>
								<td>{{ \Carbon\Carbon::parse($movement->fecha)->format('d-m-Y') }}</td>
								<td>
									<form action="{{ route('nomina_concepts.destroy', $movement->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este movimiento?');">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-danger btn-sm"> Eliminar</button>
									</form>
								</td>							
							</tr>
						@endforeach
					</tbody>
				</table>
				
			@endif
			
        </div>
    </div>
	
<script>
        document.addEventListener('DOMContentLoaded', function () {
            const conceptSelect = document.getElementById('concept_id');
            const employeeSelect = document.getElementById('employee_id');
            const montoGroup = document.getElementById('monto-group');
			const calculoButton = document.getElementById('calculoButton');
			const count = {{ $count }};
			
        if (count > 0) {
            calculoButton.disabled = true;
	
        }			

            conceptSelect.addEventListener('change', function () {
                const selectedEmployee = employeeSelect.value;

                if (!selectedEmployee) {
                    alert('Por favor, seleccione un empleado antes de seleccionar un concepto.');
                    conceptSelect.value = ''; // Resetear el valor del selector de conceptos
                    employeeSelect.classList.add('is-invalid'); // Agregar clase de alerta
                    return;
                } else {
                    employeeSelect.classList.remove('is-invalid'); // Quitar clase de alerta si ya no es necesario
                }

                const selectedConcept = parseInt(this.value);
				
				if(selectedConcept === 7) {
                    // Cambiar a campo de días pagados
                    montoGroup.innerHTML ='<label for="diasPagados">Número de días a pagar</label>';
                    montoGroup.innerHTML += '<select name="diasPagados" id="diasPagados" class="form-control" required> <option value="2"> 2 días (incluye prima domical) </option> </select>';

										
                } else if (selectedConcept === 52 || selectedConcept === 53 || selectedConcept === 54) {
                    // Cambiar a campo de días pagados
                    montoGroup.innerHTML ='<label for="diasPagados">ingresar número de días</label>';
                    montoGroup.innerHTML += '<input type="number" name="diasPagados" id="diasPagados" class="form-control" required>';
					document.getElementById("diasPagados").focus();
                } else {
                    // Volver al campo monto
                    montoGroup.innerHTML ='<label for="monto">Monto</label>';
                    montoGroup.innerHTML += '<input type="number" step="0.01" name="monto" id="monto" class="form-control" required>';
                    document.getElementById("monto").focus();
                }
            });
        });
    </script>
	
<style>
	.is-invalid {
		border-color: #dc3545;
	}
</style>	
@endsection
