@extends('layouts.main')

@section('content')

	<div class="card">
		
		<div class="card-header">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h2>Puestos y salarios</h2>
			
			</div> 		
		</div>
		
		<div class="card-body">

			<div class="row">

				<div class="table-responsive">  
				
					<table class="table table-striped table-bordered table-hover" id="example1" style="width:100%; font-size:11pt">
					
						 <thead class="table-custom">
							<tr>
								<th> <i class="fas fa-list-ol"></i> </th>
								<th>Puesto</th>
								<th> Tabulador anterior </th>
								<th> Tabulador actual </th>
								<th> Salario diario </th>
							</tr>
						</thead>
						
						<tbody>
							@foreach($salaries as $salary)
								<tr>								
									<td>{{ $salary->id }}</td>
									<td>{{ $salary->puesto }}</td>
									<td>{{ number_format($salary->tab_ant,2) }}</td>
									<td>{{ number_format($salary->tab_vig,2) }}</td>
									<td>{{ number_format($salary->tab_vig / 30 ,2) }}</td>
								</tr>
							@endforeach
						</tbody>
						
					</table>
	
				</div>
		  
			</div>

		</div>
	  
	</div>

@endsection