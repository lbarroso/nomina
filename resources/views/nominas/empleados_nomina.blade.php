@extends('layouts.main')

@section('content')

<!-- Alert para mostrar el mensaje de éxito cuando se agrega una nueva nómina -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- Alert para mostrar los errores de validación -->
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card">

	<div class="card-header d-flex justify-content-between align-items-center">
		<div class="d-flex align-items-center">
			<!-- Botón de regresar -->
			<a class="btn btn-secondary mr-2" href="{{ route('nominas.index') }}">
				<i class="fas fa-arrow-left"></i> Regresar
			</a>
			<h2>Empleados Agregados a la Nómina</h2>
		</div>
		
		<div>
			<!-- Botón de agregar empleados -->
			<button class="btn btn-primary" data-toggle="modal" data-target="#agregarEmpleadoModal">
				<i class="fas fa-plus"></i> Agregar Empleados
			</button>
			
			<!-- Botón de aplicar cálculo -->
			<button class="btn btn-success" id="aplicarCalculo" data-nomina-id="{{ $nomina->id }}">
				<i class="fas fa-calculator"></i> Aplicar Cálculo
			</button>

			<!-- Botón de cerrar nómina -->
			<form action="{{ route('nominas.cerrar', $nomina->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas cerrar esta nómina? No podrá modificarse después.')">
				@csrf
				@method('PUT')
				<button type="submit" class="btn btn-danger" title="Cerrar Nómina">
					<i class="fas fa-lock"></i> Cerrar Nómina
				</button>
			</form>
		</div>
	</div>

    <div class="card-body">
        <!-- Tabla de Empleados Agregados -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Acciones</th>          
                        <th>Nombre del Empleado</th>
                        <th>CURP</th>
                        <th>Percepciones</th>
                        <th>Deducciones</th>
                        <th>Total a Pagar</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalPercepciones = 0;
                        $totalDeducciones = 0;
                        $totalPago = 0;
                    @endphp

                    @foreach($nominaconcepts as $concept)
				
                    <tr>

						<!-- Íconos de acciones -->
						<td>
							<div class="d-flex align-items-center">
								<!-- Si la nómina está cerrada, mostrar el mensaje -->
								@if($nomina->status == 'cerrada')
									<span class="badge badge-success">Calculado</span>
								@else
									<!-- Formulario de eliminación -->
									<form action="{{ route('nominas.empleado.destroy', $concept->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este empleado de la nómina?')" class="mr-2">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
											<i class="fas fa-trash"></i>
										</button>
									</form>
									
									<!-- Botón de editar -->
									<a href="#" class="btn btn-sm btn-warning mr-2" title="Editar" 
									   data-toggle="modal" 
									   data-target="#editarEmpleadoModal"
									   data-expediente="{{ $concept->expediente }}" 
									   data-nombre="{{ $concept->paterno }} {{ $concept->materno }} {{ $concept->nombre }}" 
									   data-monto="{{ $concept->percepciones }}"
									   data-id="{{ $concept->id }}">
									   <i class="fas fa-edit"></i>
									</a>
								@endif
							</div>
						</td>



                        <!-- Información del Empleado -->
                        <td>{{ $concept->paterno }} {{ $concept->materno }} {{ $concept->nombre }}</td>
                        <td>{{ $concept->curp }}</td>       
                        <!-- Monto de percepciones -->
                        <td>${{ number_format($concept->percepciones, 2) }}</td>
                        <!-- ISR deducciones -->
                        <td>${{ number_format($concept->deducciones, 2) }}</td>
                        <!-- Total a Pagar (Percepciones - Deducciones) -->
                        @php
                            $totalPagoEmpleado = $concept->percepciones - $concept->deducciones;
                            $totalPercepciones += $concept->percepciones;
                            $totalDeducciones += $concept->deducciones;
                            $totalPago += $totalPagoEmpleado;
                        @endphp
                        <td>${{ number_format($totalPagoEmpleado, 2) }}</td>
                    </tr>
                    @endforeach

                    <!-- Fila para los totales -->
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total</strong></td>
                        <td><strong>${{ number_format($totalPercepciones, 2) }}</strong></td>
                        <td><strong>${{ number_format($totalDeducciones, 2) }}</strong></td>
                        <td><strong>${{ number_format($totalPago, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ventana Modal para agregar empleados -->
<div class="modal fade" id="agregarEmpleadoModal" tabindex="-1" role="dialog" aria-labelledby="agregarEmpleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarEmpleadoModalLabel">Agregar Empleado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAgregarEmpleado" action="{{ route('nominas.agregarEmpleado', $nomina->id) }}" method="POST">
                    @csrf
                    <!-- Selector de Empleados -->
                    <div class="form-group">
                        <label for="empleado_id">Seleccionar Empleado:</label>
                        <select name="empleado_id" id="empleado_id" class="form-control" required>
                            <option value="">Seleccione un empleado</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->paterno }} {{ $employee->materno }} {{ $employee->nombre }} </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo para el monto -->
                    <div class="form-group">
                        <label for="monto">Monto:</label>
                        <input type="number" step="0.01" name="monto" id="monto" class="form-control" onfocus="this.select()" required>
                    </div>

                    <button type="submit" class="btn btn-success">Agregar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Ventana Modal para editar el empleado -->
<div class="modal fade" id="editarEmpleadoModal" tabindex="-1" role="dialog" aria-labelledby="editarEmpleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarEmpleadoModalLabel">Editar Empleado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarEmpleado" action="{{ route('nominas.editarEmpleado') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" id="editEmpleadoId"> <!-- ID del registro de nomina_concepts -->

                    <!-- Mostrar el nombre del empleado -->
                    <div class="form-group">
                        <label for="nombre">Nombre del Empleado:</label>
                        <input type="text" id="editNombreEmpleado" class="form-control" readonly>
                    </div>

                    <!-- Campo para editar el monto -->
                    <div class="form-group">
                        <label for="editMonto">Monto:</label>
                        <input type="number" step="0.01" name="monto" id="editMonto" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')

<script>

$('#editarEmpleadoModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Botón que activó la modal

    var id = button.data('id');
    var nombre = button.data('nombre');
    var monto = button.data('monto');

    var modal = $(this);
    modal.find('#editEmpleadoId').val(id);
    modal.find('#editNombreEmpleado').val(nombre);
    modal.find('#editMonto').val(monto);
});

$('#editarEmpleadoModal').on('shown.bs.modal', function () {
    $('#editMonto').trigger('focus').select();
});

</script>

<script>


document.getElementById('aplicarCalculo').addEventListener('click', function() {
    var nominaId = this.getAttribute('data-nomina-id');

	// Obtener la URL base desde Blade usando la función 'url()'
    var baseUrl = '{{ url("nominas/aplicar-calculo") }}'
	
    // Crear la URL completa
    var calculoUrl = baseUrl + '/' + nominaId;
	
    // Crear un formulario invisible para enviar la solicitud
    var form = document.createElement('form');
    form.method = 'POST';
    form.action =  calculoUrl;

  // Crear un campo oculto para CSRF token
    var csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfInput);
	
    // Añadir el formulario al cuerpo y enviarlo
    document.body.appendChild(form);
    form.submit();
});

</script>

@endsection