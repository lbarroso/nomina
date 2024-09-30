@extends('layouts.main')

@section('content')

<div class="card">

	<div class="card-header">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2>Impresión de Nómina</h2>	
			<!-- Formulario para seleccionar la semana y año -->
			<form class="form-inline" method="POST" action="{{ route('impresion.tabla') }}" id="impresionForm">
				@csrf
				<select class="form-control" name="semana" id="semanaSelect" onchange="handleSemanaChange()">
                    @for ($i = 1; $i <= $ultimaSemana; $i++)
						<option value="{{ $i }}" {{ $i == $semana ? 'selected' : '' }}>Semana {{ $i }}</option>
                    @endfor					
				</select>		
				<select class="form-control" name="year">
					<option value="{{ $year }}">{{ $year }}</option>
				</select>				
				<a href="{{ route('impresion.pdf', ['semana' => $semana]) }}" target="_blank" id="pdfButton" class="btn btn-primary"> 
					<i class="fas fa-file-pdf"></i> Imprimir PDF 
				</a>
				<div id="loading" style="display:none;">
					<img src="{{ asset('loading.gif') }}" alt="Cargando..." width="12">
				</div>				
			</form>				
		</div> 
	</div>

	<div class="card-body">
	
		@foreach($nominaconcepts as $employee)
		<!-- Tabla con información del empleado -->
		<div class="table-responsive">
			<table class="table table-dark text-uppercase" style="width:100%; background-color: #5062A0; ">
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
		</div>
		<!--./ Fin Tabla con información del empleado /-->
		@php
			$percepciones = $employee->getPercepciones($year, $calendar->almcnt, $employee->expediente, $semana); 
			$deducciones = $employee->getDeducciones($year, $calendar->almcnt, $employee->expediente, $semana);
		@endphp
	
		<div class="row">
			<div class="col-md-6">
				<!-- Tabla de percepciones -->
				<table class="table table-bordered" style="width:100%; font-size:11pt">
					<captio> <h5> Percepciones </h5> </caption>
					<thead style="background-color: #C6CFE3;"><tr> <th>Clave</th><th>Concepto</th><th>Monto</th> </tr></thead>
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
				<!-- Tabla de deducciones -->
				<captio><h5> Deducciones </h5> </caption>
					<thead style="background-color: #C6CFE3;"><tr> <th>Clave</th><th>Concepto</th><th>Monto</th> </tr></thead>
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
				<!-- Total de percepciones -->
				<table class="table table-bordered" style="width:100%; font-size:11pt">
					<tr>
						<td colspan="2" class="font-weight-bold text-center">Total de percepciones </td>
						<td class="text-right" width="25%">{{ number_format($percepciones,2) ; }}</td>
					</tr>
				</table>			
			</div>
			<div class="col-md-6">
				<!-- Total de deducciones -->
				<table class="table table-bordered" style="width:100%; font-size:11pt">
					<tr>
						<td colspan="2" class="font-weight-bold text-center">Total de deducciones </td>
						<td class="text-right" width="25%">{{ number_format($deducciones,2) ; }}</td>
					</tr>
				</table>			
			</div>
		</div>
		
		@php
			$subsidio = $employee->getSubsidioPagado($year, $calendar->almcnt, $employee->expediente, $semana, $semana);
			$isrNeto = $employee->getIsrNeto($year, $calendar->almcnt, $employee->expediente, $semana, $semana);
		@endphp
		
		<div class="row">
			<div class="col-md-6">	
				<!-- Tabla informativos -->
				<table class="table table-bordered text-muted" style="width:100%; font-size:11pt">
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
				<!-- Totales -->
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
				<!-- Total de percepciones -->
				<table class="table table-bordered">
					<tr> <td class="font-weight-bold"> Total de percepciones </td> <td class="text-right" width="25%"> {{ number_format($totalPercepciones,2) }} </td> </tr>
				</table>
			</div>
			<div class="col-md-6">
				<!-- Total de deducciones -->
				<table class="table table-bordered">
					<tr> <td class="font-weight-bold"> Total de deducciones </td> <td class="text-right" width="25%"> {{ number_format($totalDeducciones,2) }} </td> </tr>
				</table>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-6">
			</div>
			<div class="col-md-6">
				<!-- Total neto a pagar -->
				<table class="table table-bordered">
					<tr style="background-color: #E2DBD5;"> <td class="font-weight-bold">Total Neto a pagar </td> <td class="text-right" width="25%"> {{ number_format($totalPercepciones - $totalDeducciones,2) }} </td> </tr>
				</table>
			</div>
		</div>		
		
	</div>

</div>

@endsection

@section('scripts')
<script>
    function handleSemanaChange() {
        // Deshabilitar el botón de imprimir PDF
        document.getElementById('pdfButton').classList.add('disabled');
        document.getElementById('pdfButton').innerHTML = 'Cargando...';

        // Mostrar el indicador de carga
        document.getElementById('loading').style.display = 'inline-block';

        // Enviar el formulario
        document.getElementById('impresionForm').submit();
    }

    // Rehabilitar el botón de PDF después de que la página se haya cargado nuevamente
    window.onload = function() {
        document.getElementById('pdfButton').classList.remove('disabled');
        document.getElementById('pdfButton').innerHTML = '<i class="fas fa-file-pdf"></i> Imprimir PDF';
        document.getElementById('loading').style.display = 'none';
    };
</script>
@endsection

