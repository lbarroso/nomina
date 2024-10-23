<!DOCTYPE html>
<html>
<head>

    <title> Poliza Nomina PDF</title>
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
            border: 1px solid #dee2e6;
            padding: 1px;
            text-align: left;
        }
        .table-custom th {
            background-color: #002040;
            color: white;
        }
        .table-custom .table-title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            padding-bottom: 1px;
        }
        .table-custom .table-subtitle {
            font-size: 8px;
            font-weight: bold;
            padding-top: 1px;
        }
    </style>	

</head>
<body>

	<img src="{{ asset('admin/dist/img/head.jpg') }}" class="img-fluid" width="680" height="56"  style="width:100%; padding-top: 0px; padding-bottom: 0px;"/>

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
                <td class="table-subtitle">Caratula de nómina</td>
                <td>Fecha de elaboración:  {{ $nomina->fechaPagoFormatted }} </td>
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

	
	<table class="table table-bordered text-uppercase table-striped" style="font-size: 8px;">						
		<thead style="background-color: #5062A0; color:white;"><tr> <th>Cta.</th><th>Concepto</th> <th>Percepciones</th> <th>Deducciones</th> </tr></thead>
		<tbody>
			@foreach($nominaconcepts as $nominaconcept)							
			<tr> 
				<td>{{ $nominaconcept->concept_id }} </td> 
				<td>{{ $nominaconcept->concepto }} </td> 
				<td class="text-right" width="25%">									
					{{ $nominaconcept->tipo == 'percepcion' ? number_format($nominaconcept->monto,2) : ' ' }}
				</td>
				<td class="text-right" width="25%">							
					{{ $nominaconcept->tipo == 'deduccion' ? number_format($nominaconcept->monto,2) : ' ' }}									
				</td>
			</tr>
			@endforeach		
		</tbody>
		<tfoot>
			<tr> 
				<td colspan="2" class="font-weight-bold"> Totales </td> 
				<td class="text-right"> {{ number_format($totalPercepciones,2) }} </td> 
				<td class="text-right"> {{ number_format($totalDeducciones,2) }} </td> 
			</tr>
		</tfoot>
	</table>
						
	
	<br>

	<table class="table" style="width:100%; padding-top: 0px; padding-bottom: 2px; font-size:8pt">
		<tr> <td class="font-weight-bold">Total Neto a pagar </td> <td class="text-right" width="15%"> {{ number_format($totalPercepciones - $totalDeducciones,2) }} </td> </tr>
	</table>
	
	<br>
	
	<!-- Firmas Table -->
    <table class="table-custom" style="width:100%; background-color: #ffffff; border: 1px solid #dee2e6; padding-top: 0px; padding-bottom: 2px; font-size:6pt">
        <thead>
            <tr>
                <td class="font-weight-bold text-center" width="25%">ELABORO</td>
                <td class="font-weight-bold text-center" width="25%">VALIDO</td>
                <td class="font-weight-bold text-center" width="25%">AUTORIZO</td>
				@if(!empty($firma->reviso))
					<td class="font-weight-bold text-center" width="25%">VALIDO</td>
				@endif
            </tr>
        </thead>
        <tbody>
            <tr> 
                <td><br><br><br><br><br></td>
                <td><br><br><br><br><br></td>
                <td><br><br><br><br><br></td>
				@if(!empty($firma->reviso))
					<td><br><br><br><br><br></td>
				@endif
            </tr>
            <tr> 
                <td class="text-center"> {{ $firma->elaboro }} <br> {{ $firma->elaboroNombre }} </td> 
                <td class="text-center"> {{ $firma->valido }} <br> {{ $firma->validoNombre }} </td>
                <td class="text-center"> {{ $firma->autorizo }} <br> {{ $firma->autorizoNombre }} </td> 
				@if(!empty($firma->reviso))
					<td class="text-center"> {{ $firma->reviso }} <br> {{ $firma->revisoNombre }} </td> 
				@endif
            </tr>
        </tbody>
    </table>

</body>
</html>
