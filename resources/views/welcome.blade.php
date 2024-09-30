<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido al Sistema de Nómina</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .welcome-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .welcome-container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .welcome-container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .welcome-container img {
            max-width: 360px;
            margin-bottom: 20px;
        }
        .welcome-links {
            text-align: left;
            margin-top: 30px;
        }
        .welcome-links h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .welcome-links ul {
            list-style-type: none;
            padding: 0;
        }
        .welcome-links ul li {
            margin-bottom: 10px;
        }
        .welcome-links ul li a {
            color: #007bff;
            text-decoration: none;
        }
        .welcome-links ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="welcome-container">
    <img src="{{ asset('admin/dist/img/welcome.jpg') }}" alt="CCA Logo">
    <h1>Bienvenido al Sistema de Nómina</h1>
    <p>Versión Web 4.0</p>
    <a href="https://nominacomunitaria.com/public/login" class="btn btn-primary">Iniciar Sesión</a>
    
    <div class="welcome-links">
        <h2>Enlaces de Interés:</h2>
        <ul>
            <li><a href="http://www.si-nube.appspot.com/" target="_blank">SiNube</a></li>
            <li><a href="https://www.sat.gob.mx/home" target="_blank">SAT</a></li>
            <li><a href="https://idse.imss.gob.mx/imss/" target="_blank">IDSE</a></li>
            <li><a href="https://agsc.siat.sat.gob.mx/PTSC/ValidaRFC/index.jsf" target="_blank">Validar RFC</a></li>
			<li><a href="https://verificacfdi.facturaelectronica.sat.gob.mx/" target="_blank">Validar factura</a></li>
        </ul>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
