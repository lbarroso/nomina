@extends('layouts.main')

<!-- DataTables -->
@section('styles')
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="card">

    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Resultados del Acumulado de Nómina</h2>
        </div> 
    </div>

    <div class="card-body">

        @if($nominaconcepts->isEmpty())
            <p>No se encontraron resultados para los parámetros especificados.</p>
        @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-uppercase" id="example1" style="width:100%; font-size:11pt">
                <thead>
                    <tr>
                        <th>Nombre Empleado</th>
                        <th>CURP</th>
                        <th>RFC</th>
                        <th>Expediente</th>
                        <th>Puesto</th>
                        @foreach($concepts as $concept)
                            <th>{{ $concept->concepto }}</th>
                        @endforeach
                        <th>Percepcion</th>
                        <th>Deduccion</th>
                        <th>Neto pagado</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totales = array_fill(0, count($concepts), 0);
                        $totalPercepciones = 0;
                        $totalDeducciones = 0;
                    @endphp
                    @foreach($nominaconcepts as $nominaconcept)
                        @php
                            $percepcion = 0;
                            $deduccion = 0;
                        @endphp                        
                        <tr>
                            <td>{{ $nominaconcept->nombre }} {{ $nominaconcept->paterno }} {{ $nominaconcept->materno }}</td>
                            <td>{{ $nominaconcept->curp }}</td>
                            <td>{{ $nominaconcept->rfc }}</td>
                            <td>{{ $nominaconcept->expediente }}</td>
                            <td>{{ $nominaconcept->puesto }}</td>
                            @foreach($concepts as $index => $concept)
                                @php
                                    $monto = $nominaconcept->getMonto($year, $calendar->almcnt, $nominaconcept->expediente, $concept->concept_id, $semanaInicio, $semanaFin);
                                    $totales[$index] += $monto;
                                @endphp
                                <td>{{ number_format($monto, 2) }}</td>
                                
                                @if($concept->tipo == 'percepcion') 
                                    @php
                                        $percepcion += $monto;
                                    @endphp
                                @else
                                    @php
                                        $deduccion += $monto;
                                    @endphp
                                @endif                                
                            @endforeach
                            <td>{{ number_format($percepcion, 2) }}</td>
                            <td>{{ number_format($deduccion, 2) }}</td>
                            <td>{{ number_format($percepcion - $deduccion, 2) }}</td>
                        </tr>
                        @php
                            $totalPercepciones += $percepcion;
                            $totalDeducciones += $deduccion;
                        @endphp
                    @endforeach				
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">Totales</th>
                        @foreach($totales as $total)
                            <th>{{ number_format($total, 2) }}</th>
                        @endforeach
                        <th>{{ number_format($totalPercepciones, 2) }}</th>
                        <th>{{ number_format($totalDeducciones, 2) }}</th>
                        <th>{{ number_format($totalPercepciones - $totalDeducciones, 2) }}</th>
                    </tr>
                </tfoot>				
            </table>            
        </div>
        @endif
        
    </div>
    
</div>


@endsection

<!-- DataTables  & Plugins -->
@section('scripts')
<script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Page specific script -->
<script>
  $(function () {

    $("#example1").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": true,
      "buttons": ["excel"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });
</script>

@endsection