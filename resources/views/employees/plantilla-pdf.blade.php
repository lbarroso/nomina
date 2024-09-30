<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Empleados</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9pt;
        }
        .table th, .table td {
            padding: 0px;
            vertical-align: middle;
            text-align: center;
        }
        .table thead th {
            background-color: #343a40;
            color: #ffffff;
        }
        .container {
            margin-top: 0px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

	<img src="{{ asset('admin/dist/img/head.jpg') }}" class="img-fluid" width="680" height="56"  style="width:100%; padding-top: 0px; padding-bottom: 0px;"/>

	<table class="table-custom text-uppercase" style="width:100%; padding-top: 0px; padding-bottom: 2px; font-size:6pt">
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
                <td class="table-subtitle">Plantilla empleados</td>
                <td>Fecha de impresión: {{ $fecha }} </td>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered table-sm" style="font-size: 7pt;" width="100%">
        <thead>
            <tr>
                <th>Expediente</th>
                <th>Nombre Completo</th>
                <th>Puesto</th>
                <th>RFC</th>
                <th>NSS</th>
                <th>Fecha de Ingreso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->expediente }}</td>
                <td>{{ $employee->nombre }} {{ $employee->paterno }} {{ $employee->materno }}</td>
                <td>{{ $employee->salary->puesto }}</td>
                <td>{{ $employee->rfc }}</td>
                <td>{{ $employee->nss }}</td>
                <td>{{ \Carbon\Carbon::parse($employee->fechaIngreso)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


</body>
</html>
