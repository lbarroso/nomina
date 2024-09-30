@extends('layouts.main')

@section('content')

<div class="card">

	<div class="card-header">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2>Poliza de Nómina</h2>	
			<!-- Formulario para seleccionar la semana y año -->
			<form class="form-inline" method="POST" action="{{ route('poliza.tabla') }}" id="impresionForm">	
				@csrf
				<select class="form-control" name="semana" id="semanaSelect" onchange="handleSemanaChange()">
                    @for ($i = 1; $i <= $ultimaSemana; $i++)
						<option value="{{ $i }}" {{ $i == $semana ? 'selected' : '' }}>Semana {{ $i }}</option>
                    @endfor					
				</select>		
				<select class="form-control" name="year">
					<option value="{{ $year }}">{{ $year }}</option>
				</select>				
				<a href="{{ route('poliza.pdf', ['semana' => $semana]) }}" target="_blank" class="btn btn-primary" id="pdfButton"> 
					<i class="fas fa-file-pdf"></i> Imprimir PDF 
				</a>
				<div id="loading" style="display:none;">
					<img src="{{ asset('loading.gif') }}" alt="Cargando..." width="12">
				</div>					
			</form>				
		</div> 
	</div>

	<div class="card-body">
	
		<div class="table-responsive">

			<div class="row">
			
				<div class="col-md-12">

					<table class="table table-bordered">				
						<caption>
							<h5>
							@if(isset($fechaElaboracion) && !empty($fechaElaboracion))
								Fecha de elaboración: {{ $fechaElaboracion }}
							@endif
							</h5>
						</caption>
						<thead style="background-color: #5062A0; color:white;"><tr> <th>Cta.</th><th>Concepto</th> <th>Percepciones</th> <th>Deducciones</th> </tr></thead>
						<tbody class="text-uppercase">
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
					
				</div>
				
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<!-- Total neto a pagar -->
					<table class="table table-bordered">
						<tr style="background-color: #E2DBD5;"> <td class="font-weight-bold">Total Neto a pagar </td> <td class="text-right" width="50%"> {{ number_format($totalPercepciones - $totalDeducciones,2) }} </td> </tr>
					</table>
				</div>
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
