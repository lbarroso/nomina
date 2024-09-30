@extends('layouts.main')

@section('content')

<div class="card text-center">

	<div class="card-header">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2> Borrar nómina pagada</h2>
			
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
                        <li><i class="fas fa-exclamation-triangle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif		

		<p class="card-text">Esta opción eliminará los movimientos de la semana actual y los retrocederá una semana.</p>
		
		<form action="{{ route('delete.movements') }}" method="POST" id="deleteNominaForm">
			@csrf

			<div class="form-row justify-content-center">
				<div class="form-group col-md-4">
					<label for="semana">Semana actual</label>
					<select name="semana" id="semana" class="form-control" required>
						<option value="{{ $calendar->semana }}">SEMANA {{ $calendar->semana }}</option>
					</select>
				</div>
			</div>

			<button type="button" class="btn btn-danger" id="calculoButton">
				<i class="fas fa-trash-alt"></i> Borrar Movimientos
			</button>
		</form>
		
	</div>
	
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirmación borrar nómina pagada</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminará los movimientos de la semana {{ $calendar->semana }} y retroceder una semana. {{ $calendar->semana -1 }} ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmButton">Borrar</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
		
        const deleteNominaForm = document.getElementById('deleteNominaForm');
        const confirmButton = document.getElementById('confirmButton');

        calculoButton.addEventListener('click', function() {
            $('#confirmModal').modal('show');
        });

        confirmButton.addEventListener('click', function() {
            deleteNominaForm.submit();
        });
    });
</script>
	
@endsection

