@extends('layouts.main')

@section('content')

<div class="card">

    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Cierre de Nómina</h2>
        </div>        
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-triangle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form id="cierreNominaForm" action="{{ route('close.nomina') }}" method="POST">
            @csrf

            <div class="form-row mb-3">
                <div class="form-group col-md-6">
                    <label for="semana"><i class="fas fa-calendar-week"></i> Semana Actual</label>
                    <select name="semana" id="semana" class="form-control" required>
                        <option value="{{ $calendar->semana }}">SEMANA {{ $calendar->semana }}</option>
                    </select>
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="form-group col-md-4">
                    <label for="fecha_inicio"><i class="fas fa-calendar-day"></i> Fecha de Inicio</label>
                    <input type="text" id="fecha_inicio" class="form-control" value="{{ \Carbon\Carbon::parse($calendar->fechaInicio)->format('d-m-Y') }}" disabled>
                </div>

                <div class="form-group col-md-4">
                    <label for="fecha_fin"><i class="fas fa-calendar-day"></i> Fecha de Fin</label>
                    <input type="text" id="fecha_fin" class="form-control" value="{{ \Carbon\Carbon::parse($calendar->fechaFin)->format('d-m-Y') }}" disabled>
                </div>
            </div>

            <button type="button" class="btn btn-success" id="calculoButton">
                <i class="fas fa-lock"></i> Aplicar Cierre de Nómina
            </button>
        </form>
    </div>
    
	<div class="card-footer bg-light">
		<div class="form-group col-md-12">
			<div id="alerta"></div>
		</div>
	</div>
	
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirmación de Cierre de Nómina</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas aplicar el cierre de nómina semana {{ $calendar->semana }}?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="confirmButton">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alerta = document.getElementById('alerta');
        const count = {{ $count }};
        const calculoButton = document.getElementById('calculoButton');
        const cierreNominaForm = document.getElementById('cierreNominaForm');
        const confirmButton = document.getElementById('confirmButton');

        if (count <= 0) {
            calculoButton.disabled = true;
        }

        calculoButton.addEventListener('click', function() {
            $('#confirmModal').modal('show');
        });

        confirmButton.addEventListener('click', function() {
            cierreNominaForm.submit();
        });
    });
</script>

@endsection

<style>
   .card-body {
        background-color: #f8f9fa;
    }
    .card-header {
        background-color: #007bff;
    }
    .form-check-label {
        margin-left: 1.25rem;
    }
    .alert a.link {
        color: #007bff;
        text-decoration: underline;
    }
</style>