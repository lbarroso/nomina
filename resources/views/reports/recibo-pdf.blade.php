<!DOCTYPE html>
<html>
<head>
    <title> Nomina PDF</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 10px;
        }
        .header, .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }
        .header {
            top: 0px;
        }
        .footer {
            bottom: 0px;
        }

    </style>
	<style>
        .table-custom {
            margin: 2px auto;
            width: 100%;
            border-collapse: collapse;

        }
        .table-custom th, .table-custom td {
            border: 1px solid #212529;
            padding: 1px;
            color: #212529;
            font-size: 7pt;
            vertical-align: top;
            text-align: left;
        }
        .table-custom th {
            background-color: #fff;
            color: #000;
        }
	</style>	
</head>
<body>
	
	@foreach($nominaconcepts as $employee)
	
		<div class="text-center">
			<h4> RECIBO DE NÓMINA </h4>
		</div>
		
		<img src="{{ asset('admin/dist/img/head_black.jpg') }}" class="img-fluid"  style="width:100%; padding-top: 0px; padding-bottom: 0px;">

		<table class="table-custom">
			<tr> 
				<td> RFC Empleador </td> <td  class="text-right"> {{ $company->rfc }} </td> 
				<td> Sucursal</td> <td  class="text-right"> {{ Auth::user()->regnom }} </td>
			</tr>					
			<tr> 
				<td> Lugar de expedición </td> <td  class="text-right"> {{ $company->codigoPostal }} </td> 
				<td> Unidad operativa </td> <td  class="text-right"> {{ Auth::user()->uonom }} </td>
			</tr>
			<tr> 
				<td> Tipo de comprobante </td> <td  class="text-right"> N - NÓMINA </td> 
				<td> Area </td> <td  class="text-right"> {{ Auth::user()->almacen }} </td>
			</tr>
			<tr> 
				<td> Fecha de impresión </td> <td  class="text-right"> <?php echo date('d-m-Y'); ?> </td> 
				<td> Puesto </td> <td  class="text-right"> {{ $employee->puesto }}</td>
			</tr>
			<tr> 
				<td> Moneda </td> <td  class="text-right"> MXN </td> 
				<td> Fecha inicial de pago </td> <td  class="text-right"> {{ $periodo->fecha_inicio_formatted }} </td>
			</tr>
			@php
				 $antiguedad = \Carbon\Carbon::parse($employee->fechaIngreso)->diffInYears(date('d-m-Y'));
			@endphp
			<tr> 
				<td> Antigüedad </td> <td  class="text-right"> {{ floor($antiguedad) }} años </td> 
				<td> Frecuencia de pago </td> <td  class="text-right"> semanal </td> 
			</tr>
			<tr> 
				<td> Salario base de cotización </td> <td  class="text-right"> N - NÓMINA </td> 
				<td> Fecha final de pago </td> <td  class="text-right"> {{ $periodo->fecha_fin_formatted }} </td>
			</tr>
			<tr> 
				<td> Inicio de la relación laboral </td> <td  class="text-right"> {{\Carbon\Carbon::parse($employee->fechaIngreso)->format('d-m-Y') }} </td> 
				<td> Número de días pagados </td> <td  class="text-right"> {{ $calendar->diasPagados }} </td>
			</tr>
		
		</table>
		
		<p class="font-weight-bold" style="font-size:8pt"> Empleado </p>
		
		<table style="width:100%; border: 1px solid #dee2e6; padding-top: 0px; padding-bottom: 0px; font-size:9pt" border="0">
			<tr> 
				<td class="font-weight-bold" width="10%"> Número</td> 
				<td class="text-right" width="50%"> {{ $employee->expediente }} </td> 
				<td  class="font-weight-bold" width="10%"> CURP</td> 
				<td class="text-right"> {{ $employee->curp }} </td> 
			</tr>
			<tr> 
				<td class="font-weight-bold" > Nombre</td> 
				<td class="text-right"> {{ $employee->paterno }} {{ $employee->materno }} {{ $employee->nombre }} </td> 
				<td class="font-weight-bold"> NSS</td> 
				<td class="text-right"> {{ $employee->nss }} </td> 
			</tr>
			<tr> 
				<td class="font-weight-bold"> RFC</td> 
				<td class="text-right"> {{ $employee->rfc }} </td> 
				<td colspan="2"> &nbsp; </td>
			</tr>
		</table>

		<!--./ Fin Tabla con información del empleado /-->
			
		@php
			$percepciones = $employee->getPercepciones($year, $calendar->almcnt, $employee->expediente, $semana); 
			$deducciones = $employee->getDeducciones($year, $calendar->almcnt, $employee->expediente, $semana);
		@endphp
	
		<table style="width:100%;" class="table-custom">
			<tr>
				<td width="50%">

				<!-- Tabla de percepciones -->
				<table class="table-custom">
					<caption>  Perceciones  </caption>
					<thead><tr> <th>Clave</th><th>Concepto</th><th>Monto</th> </tr></thead>
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
				
				</td>
				<td width="50%">
				
				<!-- Tabla de deducciones -->
				<table class="table-custom">
				<caption> Deducciones  </caption>
					<thead><tr> <th>Clave</th><th>Concepto</th><th>Monto</th> </tr></thead>
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
				
				</td>
			</tr>
		</table>

		@php
			$percepciones = $percepciones->sum('monto');
			$deducciones = $deducciones->sum('monto');
		@endphp	
		
		<table  style="width:100%; font-size:9pt">
			<tr>
				<td colspan="2" class="font-weight-bold text-center">Total de percepciones </td>
				<td class="text-right" width="25%">{{ number_format($percepciones,2) ; }}</td>
				<td colspan="2" class="font-weight-bold text-center">Total de deducciones </td>
				<td class="text-right" width="25%">{{ number_format($deducciones,2) ; }}</td>						
			</tr>
		</table>			
	
		@php
			$subsidio = $employee->getSubsidioPagado($year, $calendar->almcnt, $employee->expediente, $semana, $semana);
			$isrNeto = $employee->getIsrNeto($year, $calendar->almcnt, $employee->expediente, $semana, $semana);
		@endphp		

		<br>

		<table style="width:100%; font-size:9pt">
		<tr>
			<td width="50%">
			<!-- Tabla informativos -->
			<table style="width:100%; border: 1px solid #dee2e6;" class="text-muted">
				<tr> <td colspan="3"> Informativos </td> </tr>
				<tr>
					<td width="5%">98</td>
					<td>ISR NETO  </td> 
					<td class="text-right" width="25%"> {{ number_format($isrNeto,2); }} </td>
				</tr>
				<tr>
					<td width="5%">99</td>
					<td > SUBSIDIO PARA EL EMPLEO </td> 
					<td class="text-right" width="25%"> {{ number_format($subsidio,2); }} </td>
				</tr>								
			</table>				
			
			</td>
		<td>
		<!-- Totales -->
		<table style="width:100%; border: 1px solid #dee2e6;">
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
		</td>
		</tr>
		</table>

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

		<br>
		<br>
		<br>
	
	@endforeach	

</body>
</html>
