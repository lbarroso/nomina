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
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Por favor corrige los siguientes errores:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Nóminas Extraordinarias y Extemporáneas</h2>
            <!-- Botón que abre la ventana modal -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#nuevaNominaModal"> <i class="fas fa-plus"></i> Agregar Nueva Nómina </button>
        </div>
        <!-- Filtros -->
        <form action="{{ route('nominas.index') }}" method="GET" class="row mb-3">
            <div class="col-md-3">
                <label for="fechaInicio">Fecha de Inicio :</label>
                <input type="date" name="fechaInicio" id="fechaInicio" class="form-control" value="{{ request('fechaInicio', \Carbon\Carbon::today()->format('Y-m-d')) }}">
            </div>
        
            <div class="col-md-3">
                <label for="fechaFin">Fecha de Fin:</label>
                <input type="date" name="fechaFin" id="fechaFin" class="form-control" value="{{ request('fechaFin', \Carbon\Carbon::today()->format('Y-m-d')) }}">
            </div>
        
            <div class="col-md-3">
                <label for="tipo_nomina">Tipo de Nómina:</label>
                <select name="tipo_nomina" id="tipo_nomina" class="form-control">
                    <option value="">Todos</option>
                    <option value="regular" {{ request('tipo_nomina') == 'regular' ? 'selected' : '' }}>Regular</option>
                    <option value="extraordinaria" {{ request('tipo_nomina') == 'extraordinaria' ? 'selected' : '' }}>Extraordinaria</option>
                    <option value="extemporanea" {{ request('tipo_nomina') == 'extemporanea' ? 'selected' : '' }}>Extemporánea</option>
                </select>
            </div>
        
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block"> <i class="fas fa-filter"></i> Filtrar</button>
            </div>
        </form>
        
        <!-- Fin de Filtros -->        
    </div>

    <div class="card-body">
        <!-- Contenido de la tabla -->
        <div class="row">
		
			<div class="table-responsive">  
				<table class="table table-striped table-bordered table-hover" id="nominasTable" style="width:100%;">
					<thead class="table-custom">
						<tr>
							<th>Motivo</th>
							<th>Tipo de Nómina</th>
							<th>Fecha Inicio</th>
							<th>Fecha Fin</th>
							<th>Días Pagados</th>
							<th>Status</th>
							<th style="width: 180px;">Acciones</th> <!-- Ajustamos el ancho de la columna de acciones -->
						</tr>
					</thead>
					
					<tbody>
						@foreach($nominas as $nomina)
							<tr>
								<td>{{ $nomina->motivo }}</td> <!-- Motivo -->
								<td>{{ ucfirst($nomina->tipo_nomina) }}</td> <!-- Tipo de Nómina -->
								<td>{{ $nomina->fechaInicioFormatted ?? 'N/A' }}</td> <!-- Fecha Inicio usando el accesor -->
								<td>{{ $nomina->fechaFinFormatted ?? 'N/A' }}</td> <!-- Fecha Fin usando el accesor -->
								<td>{{ $nomina->diasPagados }} días</td> <!-- Días Pagados -->
								<td class="{{ $nomina->status == 'cerrada' ? 'text-muted' : '' }}">
									{{ ucfirst($nomina->status) }}
								</td>                                
								<td class="d-flex justify-content-between align-items-center"> <!-- Flexbox para alinear horizontalmente -->
									@if($nomina->status == 'cerrada')
										<!-- Menú desplegable para opciones de impresión -->
										<div class="dropdown">
											<button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton{{ $nomina->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fas fa-print"></i>
											</button>
											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $nomina->id }}">
												<a class="dropdown-item" href="{{ route('recibo.nomina.pdf', ['id' => $nomina->id]) }}" target="_blank">
													<i class="fas fa-file-pdf"></i> Recibos PDF
												</a>
												<a class="dropdown-item" href="{{ route('poliza.nomina.pdf', ['id' => $nomina->id]) }}" target="_blank">
													<i class="fas fa-file-pdf"></i> Póliza PDF
												</a>
												<a class="dropdown-item" href="{{ route('impresion.nomina.pdf', ['id' => $nomina->id]) }}" target="_blank">
													<i class="fas fa-file-pdf"></i> Carátula PDF
												</a>
											</div>
										</div>

										<!-- Icono de Descargar XLS -->
										<a href="{{ route('plantilla.nomina.especial.store', ['id' => $nomina->id]) }}" class="btn btn-sm btn-success ml-1" title="Descargar plantilla XLS">
											<i class="fas fa-file-excel"></i>
										</a>
									@endif

									<!-- Icono de Editar (Siempre disponible) -->
									<a href="{{ route('nominas.empleados', $nomina->id) }}" class="btn btn-sm btn-warning ml-1" title="Editar">
										<i class="fas fa-edit"></i>
									</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

		</div>
        <!-- Paginación -->
        <div class="d-flex justify-content-center">
            {{ $nominas->links() }} <!-- Mostrar los enlaces de paginación -->
        </div>
    </div>
</div>

<!-- Ventana Modal para agregar una nueva nómina -->
<div class="modal fade" id="nuevaNominaModal" tabindex="-1" role="dialog" aria-labelledby="nuevaNominaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="nuevaNominaLabel">Agregar Nueva Nómina</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Formulario para agregar nueva nómina -->
          <form action="{{ route('nominas.store') }}" method="POST" id="formNuevaNomina">
              @csrf
              
              <!-- Motivo -->
              <div class="form-group">
                  <label for="motivo">Motivo/Concepto:</label>
				  <input type="text" name="motivo" id="motivo" class="form-control" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()" required>

              </div>
              
              <!-- Periodicidad y Tipo de Nómina (en un solo renglón) -->
              <div class="form-group row">
                  <div class="col-md-6">
                      <label for="periodicidad">Periodicidad:</label>
                      <select name="periodicidad" id="periodicidad" class="form-control" required>
                          
                          <option value="especial" selected>Especial</option>
                      </select>
                  </div>
                  <div class="col-md-6">
                      <label for="tipo_nomina">Tipo de Nómina:</label>
                      <select name="tipo_nomina" id="tipo_nomina" class="form-control" required>       
                          <option value="extraordinaria" selected>Extraordinaria</option>
                      </select>
                  </div>
              </div>
  
              <!-- Fecha de Inicio -->
              <div class="form-group">
                  <label for="fechaInicio">Fecha de Inicio:</label>
                  <input type="date" name="fechaInicio" id="dateInicio" value="" class="form-control" required>
              </div>
  
              <!-- Fecha de Fin -->
              <div class="form-group">
                  <label for="fechaFin">Fecha de Fin:</label>
                  <input type="date" name="fechaFin" id="dateFin" value="" class="form-control" required>
              </div>
  
              <!-- Días Pagados (calculado automáticamente con JavaScript) -->
              <div class="form-group">
                  <label for="diasPagados">Días Pagados:</label>
                  <input type="number" name="diasPagados" id="diasPagados" class="form-control" >
              </div>
  
              <!-- Concepto (select relacionado con concepts) -->
              <div class="form-group">
                  <label for="concept_id">Concepto:</label>
                  <select name="concept_id" id="concept_id" class="form-control text-uppercase" required>
                      <option value="">Seleccione un concepto</option>
                      @foreach($concepts as $concept)
                          <option value="{{ $concept->id }}">{{ $concept->descripcion }}</option>
                      @endforeach
                  </select>
              </div>
  
              <!-- Campo Monto (mostrado solo si concept_id es 43) -->
              <div class="form-group" id="montoField" style="display:none;">
                  <label for="monto">Monto:</label>
                  <input type="number" step="0.01" name="monto" id="monto" class="form-control">
              </div>
  
              <button type="submit" class="btn btn-success">Guardar Nómina</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  
@endsection


@section('scripts')
  
<script>
    // Calcular los días entre la fecha de inicio y fecha de fin
    document.getElementById('dateInicio').addEventListener('change', calcularDiasPagados);
    document.getElementById('dateFin').addEventListener('change', calcularDiasPagados);

    function calcularDiasPagados() {

        var fechaInicio = document.getElementById('dateInicio').value;
        var fechaFin = document.getElementById('dateFin').value;
        
        if (fechaInicio && fechaFin) {
            var date1 = new Date(fechaInicio);
            var date2 = new Date(fechaFin);
            var timeDifference = date2.getTime() - date1.getTime();
            var daysDifference = timeDifference / (1000 * 3600 * 24) + 1; // Se suma 1 para incluir ambos días

            if (daysDifference > 0) {
                document.getElementById('diasPagados').value = daysDifference;
            } else {
                document.getElementById('diasPagados').value = 0;
            }
        }
    }

    // Mostrar el campo "Monto" si el concept_id es 43
    document.getElementById('concept_id').addEventListener('change', function() {
        var conceptId = document.getElementById('concept_id').value;
        var montoField = document.getElementById('montoField');

        if (conceptId == 43) {
            montoField.style.display = 'block';
        } else {
            montoField.style.display = 'none';
        }
    });
    </script>


@endsection