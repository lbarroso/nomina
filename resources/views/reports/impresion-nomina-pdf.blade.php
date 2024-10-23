<!DOCTYPE html>
<html>
<head>
    <title> Nomina Especial PDF</title>
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
			border: 1px solid #e2dbd5;
			padding: 1px;
			color: #1b263b;
		}
		.table-custom th {
			background-color: #002040;
			color: white;
		}
	</style>	
</head>
<body>
	
	<img src="{{ asset('admin/dist/img/head.jpg') }}" class="img-fluid" width="680" height="56"  style="width:100%; padding-top: 0px; padding-bottom: 0px;">

	<table class="table-custom text-uppercase" style="width:100%; padding-top: 0px; padding-bottom: 2px; font-size:7pt">
        <thead>
            <tr>
                <th colspan="2" class="table-title"> {{ $company->nombre }} </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="table-subtitle" width="15%">R.F.C.</td>
                <td> {{ $company->rfc }} </td>
            </tr>
            <tr>
                <td class="table-subtitle">Dirección</td>
                <td> {{ $company->calle }} {{ $company->calleNo }} {{ $company->localidad }} {{ $company->municipio }} {{ $company->codigoPostal }} {{ $company->estado }}</td>
            </tr>
            <tr>
                <td class="table-subtitle">Plantilla Nómina</td>
                <td>Fecha de elaboración: {{ $nomina->fechaPagoFormatted }} </td>
            </tr>
            <tr>
                <td class="table-subtitle">Periodo</td>
                <td> {{ $nomina->periodicidad }} del  {{ $nomina->fechaInicioFormatted }} al {{ $nomina->fechaFinFormatted }} </td>
            </tr>
            <tr>
                <td class="table-subtitle">Motivo</td>
                <td> {{ $nomina->motivo }} </td>
            </tr>				
        </tbody>
    </table>
	
	@if($numeroDeRegistros < 12)
		<br>
	@endif
	
	<table class="{{ $numeroDeRegistros < 12 ? 'table table-striped' : 'table-custom text-uppercase' }}" style="width:100%; border: 1px solid #dee2e6; padding-top: 0px; padding-bottom: 0px; font-size:7pt" border="0">
		<thead class="thead-dark"><tr> <th>EXP.</th><th>NOMBRE</th><th>PUESTO</th><th>MONTO $</th> </tr></thead>
		<tbody>
		@foreach($nominaconcepts as $employee)
			<tr>                    
				<td width="10%">{{ $employee->expediente }}</td>
				<td class="text-uppercase">{{ $employee->nombre }} {{ $employee->paterno }} {{ $employee->materno }} </td>
				<td>{{ $employee->puesto }}</td>
				<td class="text-right" width="15%">{{ number_format($employee->percepcion - $employee->deduccion, 2) }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>

	<table class="{{ $numeroDeRegistros < 12 ? 'table' : 'table-custom' }}" style="width:100%; padding-top: 0px; padding-bottom: 2px; font-size:8pt">
		<tr> <td class="font-weight-bold">Total Neto a pagar </td> <td class="text-right" width="15%"> {{ number_format($totalPercepciones - $totalDeducciones,2) }} </td> </tr>
	</table>
	
	@if($numeroDeRegistros < 12)
		<br>
	@endif
	
	<!-- Firmas Table -->
    <table class="table-custom" style="width:100%; background-color: #ffffff; border: 1px solid #dee2e6; padding-top: 0px; padding-bottom: 2px; font-size:6pt">
        <thead>
            <tr>
                <td class="font-weight-bold text-center" width="25%">ELABORO</td>
                <td class="font-weight-bold text-center" width="25%">VALIDO</td>
                <td class="font-weight-bold text-center" width="25%">AUTORIZO</td>
				@if(!empty($firma->revisoNombre))
					<td class="font-weight-bold text-center" width="25%">VALIDO</td>
				@endif
            </tr>
        </thead>
        <tbody>
            <tr> 
                <td><br><br><br><br><br></td>
                <td><br><br><br><br><br></td>
                <td><br><br><br><br><br></td>
				@if(!empty($firma->revisoNombre))
					<td><br><br><br><br><br></td>
				@endif
            </tr>
            <tr> 
                <td class="text-center"> {{ $firma->elaboro }} <br> {{ $firma->elaboroNombre }} </td> 
                <td class="text-center"> {{ $firma->valido }} <br> {{ $firma->validoNombre }} </td>
                <td class="text-center"> {{ $firma->autorizo }} <br> {{ $firma->autorizoNombre }} </td> 
				@if(!empty($firma->revisoNombre))
					<td class="text-center"> {{ $firma->reviso }} <br> {{ $firma->revisoNombre }} </td> 
				@endif
            </tr>
        </tbody>
    </table>

</body>
</html>
