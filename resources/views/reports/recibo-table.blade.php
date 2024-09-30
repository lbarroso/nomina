@extends('layouts.main')

@section('styles')
<style>
	.table-custom {
		margin: 2px auto;
		width: 100%;
		border-collapse: collapse;
		background-color: #c6cfe3;
	}
	.table-custom th, .table-custom td {
		border: 1px solid #e2dbd5;
		padding: 1px;
		color: #1b263b;
		
	}
	.table-custom th {
		background-color: #002040;
		color: white;
	}
</style>
@endsection

@section('content')

<div class="card">

	<div class="card-header">
	
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2>Recibos de Nómina</h2>	
			<!-- Formulario para seleccionar la semana y año -->
			<form class="form-inline" method="POST" action="{{ route('recibo.tabla') }}" id="impresionForm">	
				@csrf
				<select class="form-control" name="semana" id="semanaSelect" onchange="handleSemanaChange()">
                    @for ($i = 1; $i <= $ultimaSemana; $i++)
						<option value="{{ $i }}" {{ $i == $semana ? 'selected' : '' }}>Semana {{ $i }}</option>
                    @endfor					
				</select>		
				<select class="form-control" name="year">
					<option value="{{ $year }}">{{ $year }}</option>
				</select>				
				<a href="{{ route('recibo.pdf', ['semana' => $semana]) }}" target="_blank" id="pdfButton" class="btn btn-primary"> 
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
	
		<div class="text-center">
			<h1> RECIBO DE NÓMINA </h1>
		</div>
		
		<div class="text-center">
			<img src="{{ asset('admin/dist/img/head.jpg') }}" class="img-fluid"  style="width:100%; padding-top: 0px; padding-bottom: 0px;">
		</div>
		
		<div class="row">
			<div class="col-md-6">
				<table class="table-custom">
					<tr> <td> RFC Empleador </td> <td  class="text-right"> {{ $company->rfc }} </td> </tr>					
					<tr> <td> Lugar de expedición </td> <td  class="text-right"> {{ $company->codigoPostal }} </td> </tr>
					<tr> <td> Tipo de comprobante </td> <td  class="text-right"> N - NÓMINA </td> </tr>
					<tr> <td> Fecha de impresión </td> <td  class="text-right"> <?php echo date('d-m-Y'); ?> </td> </tr>
					<tr> <td> Moneda </td> <td  class="text-right"> MXN </td> </tr>
					@php
						 $antiguedad = \Carbon\Carbon::parse($employee->fechaIngreso)->diffInYears(date('d-m-Y'));
					@endphp
					<tr> <td> Antigüedad </td> <td  class="text-right"> {{ floor($antiguedad) }} años </td> </tr>
					<tr> <td> Salario base de cotización </td> <td  class="text-right"> N - NÓMINA </td> </tr>
					<tr> <td> Inicio de la relación laboral </td> <td  class="text-right"> {{\Carbon\Carbon::parse($employee->fechaIngreso)->format('d-m-Y') }} </td> </tr>				
				</table>
			</div>
			<div class="col-md-6">
				<table class="table-custom">
					<tr> <td> Sucursal</td> <td  class="text-right"> {{ Auth::user()->regnom }} </td> </tr>
					<tr> <td> Unidad operativa </td> <td  class="text-right"> {{ Auth::user()->uonom }} </td> </tr>
					<tr> <td> Area </td> <td  class="text-right"> {{ Auth::user()->almacen }} </td> </tr>
					<tr> <td> Puesto </td> <td  class="text-right"> {{ $employee->puesto }}</td> </tr>
					<tr> <td> Fecha inicial de pago </td> <td  class="text-right"> {{ $periodo->fecha_inicio_formatted  }} </td> </tr>
					<tr> <td> Frecuencia de pago </td> <td  class="text-right"> semanal </td> </tr>				
					<tr> <td> Fecha final de pago </td> <td  class="text-right"> {{ $periodo->fecha_fin_formatted  }} </td> </tr>
					<tr> <td> Número de días pagados </td> <td  class="text-right"> {{ $calendar->diasPagados }} </td> </tr>				
				</table>		
			</div>
		</div> <!--row-->
		
		<br>
		
		<h3>Empleado</h3>
		<div class="row">
			<div class="col-md-6">
				<table class="table table-bordered">
					<tr> <td width="20%" class="font-weight-bold"> Número</td> <td class="text-right"> {{ $employee->expediente }} </td> </tr>
					<tr> <td class="font-weight-bold"> Nombre</td> <td class="text-right"> {{ $employee->paterno }} {{ $employee->materno }} {{ $employee->nombre }} </td> </tr>
					<tr> <td class="font-weight-bold"> RFC</td> <td class="text-right"> {{ $employee->rfc }} </td> </tr>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-bordered">
					<tr> <td width="20%" class="font-weight-bold"> CURP</td> <td class="text-right"> {{ $employee->curp }} </td> </tr>
					<tr> <td class="font-weight-bold"> NSS</td> <td class="text-right"> {{ $employee->nss }} </td> </tr>
					<tr> <td colspan="2"> &nbsp; </td></tr>
				</table>			
			</div>			
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
		
		<br><br>

		<div class="row">
			<div class="col-md-12">
				<table class="" style="width:100%;">
					<tbody>
						<tr> <td class="text-center"> __________________________</td> </tr>
						<tr> <td class="text-center"> Firma del empleado </td> </tr>
					</tbody>
				</table>			
			</div>
		</div>
		
		<br><br><br><br>

		<hr>
	
	@endforeach
	
	</div> <!--card-->
	
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