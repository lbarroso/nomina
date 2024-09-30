@extends('layouts.main')


@section('content')

	<div class="card">
		
		<div class="card-header">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h2> Calendario de pago semanal </h2>
				
			</div> 		
		</div>
		
		<div class="card-body">

			<div class="row">

				<div class="table-responsive">  
				
					<table class="table table-striped table-bordered table-hover" id="example1" style="width:100%; ">
					
						 <thead class="table-custom">
							<tr>
								<th> Status </th>
								<th> Semana  </th>
								<th> Mes </th>
								<th> Fecha inicio </th>
								<th> Fecha fin </th>
								<th> Días pagados </th>
								<th> Días bimestre </th>
							</tr>
						</thead>
						
						<tbody>
							@foreach($calendars as $calendar)
								<tr class="{{ $calendar->puntero == 1 ? 'text-light bg-primary' : '' }}">	
									<td>
										@if($calendar->status == 1)
											<i class="fas fa-check-circle text-success" title="Activo"></i> <small> pagada </small>
										@else
											<i class="fas fa-times-circle text-muted" title="No Activo"></i> <small class="text-muted"> {{ $calendar->puntero == 1 ? 'actual' : 'pendiente' }} </small>
										@endif									
									</td>
									<td>{{ $calendar->semana }}</td>
									<td>{{ $calendar->nombre_mes }}</td> <!-- Usar el método accesor -->
									<td>{{ $calendar->fecha_inicio_formatted }}</td> <!-- Usar el método accesor para la fecha formateada -->
									<td>{{ $calendar->fecha_fin_formatted }}</td>
									<td>{{ $calendar->diasPagados }} días </td>
									<td>{{ $calendar->bimestre }} días</td>
								</tr>
							@endforeach
						</tbody>
						
					</table>
	
				</div>
		  
			</div>

		</div>
	  
	</div>

@endsection