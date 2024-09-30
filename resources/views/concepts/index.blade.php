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
				<h2> Conceptos de nómina </h2>
				
			</div> 		
		</div>
		
		<div class="card-body">

			<div class="row">

				<div class="table-responsive">  
				
					<table class="table table-striped table-bordered table-hover" id="example1"  style="width:100%; font-size:11pt">
					
						 <thead class="table-custom">
							<tr>
								<th> <i class="fas fa-list-ol"></i> </th>
								<th> Concepto  </th>
								<th> Descripción  </th>
								<th> Tipo </th>
							</tr>
						</thead>
						
						<tbody>
							@foreach($concepts as $concept)
								<tr>	
									<td>{{ $concept->id }}</td>
									<td>{{ strtoupper($concept->concepto) }}</td>
									<td>{{ strtoupper($concept->descripcion) }}</td>
									<td>{{ strtoupper($concept->tipo) }}</td>
								</tr>
							@endforeach
						</tbody>
						
					</table>
	
				</div>
		  
			</div>

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
    $('#example1').DataTable();

})  
</script>

@endsection