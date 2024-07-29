@extends('layouts.main')

@section('content')

<div class="card">

	<div class="card-header">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2>Impresión de Nómina</h2>	
			<form class="form-inline" method="POST" action="{{ route('impresion.tabla') }}">	
				@csrf
				<select class="form-control" name="semana">
					<option value="{{ $calendar->year }}">{{ $calendar->year }}</option>
				</select>
				<select class="form-control" name="semana" onchange="this.form.submit()">
                    @for ($i = 1; $i <= $ultimaSemana; $i++)
						<option value="{{ $i }}" {{ $i == $semana ? 'selected' : '' }}>Semana {{ $i }}</option>
                    @endfor					
				</select>		
				<a href="#" class="btn btn-primary"> <i class="fas fa-file-pdf"></i> Imprimir PDF </a>
			</form>				
		</div> 
	</div>

	<div class="card-body">
	
		@foreach($nominaconcepts as $employee)
		<table class="table table-dark text-uppercase" style="width:100%; ">
			<tbody>
				<tr>
					<td class="font-weight-bold">Número de empleado</td>
					<td class="text-right" >{{ $employee->expediente }}</td>
					<td class="font-weight-bold">CURP</td>
					<td class="text-right">{{ $employee->curp }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Nombre</td>
					<td class="text-right">{{ $employee->nombre }} {{ $employee->paterno }} {{ $employee->materno }}</td>
					<td class="font-weight-bold">NSS</td>
					<td class="text-right">{{ $employee->nss }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">RFC</td>
					<td class="text-right">{{ $employee->rfc }}</td>
					<td class="font-weight-bold">PUESTO</td>
					<td class="text-right">{{ $employee->puesto  }}</td>
				</tr>
			</tbody>
		</table>
		
		@php
			$percepciones = $employee->getPercepciones($calendar->year, $calendar->almcnt, $employee->expediente, $calendar->semana); 
			$deducciones = $employee->getDeducciones($calendar->year, $calendar->almcnt, $employee->expediente, $calendar->semana);
		@endphp
		
		<div class="row">
			<div class="col-md-6">
				<table class="table table-bordered" style="width:100%; font-size:11pt">
					<captio> <h5> Perceciones </h5> </caption>
					<thead class="thead-dark"><tr> <th>Clave</th><th>Concepto</th><th>Monto</th> </tr></thead>
					<tbody class="text-uppercase">
						@foreach($percepciones as $percepcion)				
						<tr> 
							<td>{{ $percepcion->concept_id }} </td> 
							<td>{{ $percepcion->concept->descripcion }} </td> 
							<td class="text-right" width="25%">{{ number_format($percepcion->monto,2) }} </td>
						</tr>	
						@endforeach		
					</tbody>
				</table>		
			</div>
			<div class="col-md-6">
				<table class="table table-bordered " style="width:100%; font-size:11pt">
				<captio><h5> Deducciones </h5> </caption>
					<thead class="thead-dark"><tr> <th>Clave</th><th>Concepto</th><th>Monto</th> </tr></thead>
					<tbody class="text-uppercase">
						@foreach($deducciones as $deduccion)				
						<tr> 
							<td>{{ $deduccion->concept_id }} </td> 
							<td>{{ $deduccion->concept->descripcion }} </td> 
							<td class="text-right" width="25%">{{ number_format($deduccion->monto,2) }} </td>
						</tr>	
						@endforeach	
					</tbody>
				</table>			
			</div>	
		</div>
		@php
		$percepciones = $percepciones->sum('monto');
		$deducciones = $deducciones->sum('monto');
		@endphp		
		<div class="row">
			<div class="col-md-6">
				<table class="table table-bordered" style="width:100%; font-size:11pt">
					<tr>
						<td colspan="2" class="font-weight-bold text-center">Total de percepciones </td>
						<td class="text-right" width="25%">{{ number_format($percepciones,2) ; }}</td>
					</tr>
				</table>			
			</div>
			<div class="col-md-6">
				<table class="table table-bordered" style="width:100%; font-size:11pt">
					<tr>
						<td colspan="2" class="font-weight-bold text-center">Total de deducciones </td>
						<td class="text-right" width="25%">{{ number_format($deducciones,2) ; }}</td>
					</tr>
				</table>			
			</div>
		</div>
		@php
			$subsidio = $employee->getSubsidioPagado($calendar->year, $calendar->almcnt, $employee->expediente, $calendar->semana, $calendar->semana);
			$isrNeto = $employee->getIsrNeto($calendar->year, $calendar->almcnt, $employee->expediente, $calendar->semana, $calendar->semana);
		@endphp
		
		<div class="row">
			<div class="col-md-6">	
				
				<table class="table table-bordered" style="width:100%; font-size:11pt">
					<tr> <td colspan="3"> Informativos </td> </tr>
					<tr>
						<td>98</td>
						<td>ISR NETO  </td> 
						<td class="text-right" width="25%"> {{ number_format($isrNeto,2); }} </td>
					</tr>
					<tr>
						<td>99</td>
						<td> SUBSIDIO PARA EL EMPLEO </td> 
						<td class="text-right" width="25%"> {{ number_format($subsidio,2); }} </td>
					</tr>				
				
				</table>				
			</div>
			<div class="col-md-6">	
				<table class="table table-bordered" style="width:100%; font-size:11pt">
					<tr>
						<td class="font-weight-bold">Percepciones </td> 
						<td class="text-right" width="25%"> {{ number_format($percepciones,2); }} </td>
					</tr>	
					<tr>
						<td class="font-weight-bold">Deducciones </td> 
						<td class="text-right" width="25%"> {{ number_format($deducciones,2); }} </td>
					</tr>					
					<tr>						
						<td class="font-weight-bold">Neto a pagar </td>
						<td class="text-right" width="25%">{{ number_format($percepciones - $deducciones,2); }}</td>
					</tr>					
				</table>			
			</div>			
		</div>
		
		<hr>
		@endforeach
		
		<div class="row">
			<div class="col-md-6">
				<table class="table table-bordered">
					<tr> <td class="font-weight-bold"> Total de percepciones </td> <td class="text-right" width="25%"> {{ number_format($totalPercepciones,2) }} </td> </tr>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-bordered">
					<tr> <td class="font-weight-bold"> Total de deducciones </td> <td class="text-right" width="25%"> {{ number_format($totalDeducciones,2) }} </td> </tr>
				</table>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-6">
			</div>
			<div class="col-md-6">
				<table class="table table-bordered">
					<tr> <td class="font-weight-bold">Total Neto a pagar </td> <td class="text-right" width="25%"> {{ number_format($totalPercepciones - $totalDeducciones,2) }} </td> </tr>
				</table>
			</div>
		</div>		
		
	</div>

</div>

@endsection

