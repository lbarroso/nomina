@extends('layouts.main')
  
@section('content')

	<div class="card">
		
		<div class="card-header">
			<h2>Agregar Nuevo Empleado</h2>
		</div>
		
		<div class="card-body">

			@if(session('success'))
				<div class="alert alert-success">
					{{ session('success') }}
				</div>
			@endif
	
			<form action="{{ route('employees.store') }}" method="POST" id="createEmployeeForm">
				@csrf
				<small class="form-text text-muted">validar el nombre y apellidos del empleado correspondan al acta situación fiscal SAT del empleado.</small>							
				
				<div class="form-row">
				
					<div class="form-group col-md-4">
						<label for="nombre">Nombre(*):</label>
						<input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" required>
						@error('nombre')
							<span class="text-danger">{{ $message }}</span>
						@enderror
					</div>
					<div class="form-group col-md-4">
						<label for="paterno">Apellido Paterno(*):</label>
						<input type="text" name="paterno" id="paterno" class="form-control" value="{{ old('paterno') }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" required>
						@error('paterno')
							<span class="text-danger">{{ $message }}</span>
						@enderror
					</div>
					<div class="form-group col-md-4">
						<label for="materno">Apellido Materno(*):</label>
						<input type="text" name="materno" id="materno" class="form-control" value="{{ old('materno') }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" required>
						@error('materno')
							<span class="text-danger">{{ $message }}</span>
						@enderror
					</div>
					
				</div>
							
				<div class="form-row">
				
					<div class="form-group col-md-4">
						<label for="rfc">RFC(*):</label>
						<input type="text" name="rfc" id="rfc" class="form-control" value="{{ old('rfc') }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" required>
						<small class="form-text text-muted">el RFC debe corresponder al acta situación fiscal SAT del empleado.</small>	
						@error('rfc')
							<span class="text-danger">{{ $message }}</span>
						@enderror
					</div>
					<div class="form-group col-md-4">
						<label for="curp">CURP(*):</label>
						<input type="text" name="curp" id="curp" class="form-control" value="{{ old('curp') }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" required>
						@error('curp')
							<span class="text-danger">{{ $message }}</span>
						@enderror
					</div>
					<div class="form-group col-md-4">
						<label for="nss">NSS(*):</label>
						<input type="text" name="nss" id="nss" class="form-control" value="{{ old('nss') }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" required>
						@error('nss')
							<span class="text-danger">{{ $message }}</span>
						@enderror
					</div>			
				
				</div>
				
				<div class="form-row">
				
					<div class="form-group date col-md-6">

				   <div class="form-group">
						<label for="fechaIngreso">Fecha de Ingreso(*):</label>
						<input type="date" name="fechaIngreso" id="fechaIngreso" class="form-control" value="{{ old('fechaIngreso') }}" required>
						@error('fechaIngreso')
							<span class="text-danger">{{ $message }}</span>
						@enderror
					</div>
						
					</div>
					<div class="form-group col-md-6">
						<label for="fechaNacimiento">Fecha de Nacimiento(*):</label>
						<input type="date" name="fechaNacimiento" id="fechaNacimiento" value="{{ old('fechaNacimiento') }}" class="form-control" required>
						@error('fechaNacimiento')
							<span class="text-danger">{{ $message }}</span>
						@enderror
					</div>		
					
				</div>
				
				<div class="form-row">
				
					<div class="form-group col-md-4">
						<label for="codigoPostal">Codigo postal(*):</label>
						<input type="number" name="codigoPostal" maxlength="5" onFocus="this.select()" id="codigoPostal" value="{{ old('codigoPostal') }}" class="form-control" required>
						<small class="form-text text-muted">el codigo postal debe corresponder al acta situación fiscal SAT del empleado.</small>							
						@error('codigoPostal')
							<span class="text-danger">{{ $message }}</span>
						@enderror
					</div>
					
					<div class="form-group col-md-4">
						<label for="estado">Estado(*):</label>
						<select name="estado" id="estado" class="form-control" required>
							<option value="">Seleccionar opción</option>
							@php
								$estados = [
									"Aguascalientes", "Baja California", "Baja California Sur", "Campeche", 
									"Chiapas", "Chihuahua", "Ciudad de México", "Coahuila", "Colima", 
									"Durango", "Estado de México", "Guanajuato", "Guerrero", "Hidalgo", 
									"Jalisco", "Michoacán", "Morelos", "Nayarit", "Nuevo León", 
									"Oaxaca", "Puebla", "Querétaro", "Quintana Roo", "San Luis Potosí", 
									"Sinaloa", "Sonora", "Tabasco", "Tamaulipas", "Tlaxcala", 
									"Veracruz", "Yucatán", "Zacatecas"
								];
							@endphp
							@foreach($estados as $estado)
								<option value="{{ $estado }}" {{ old('estado') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
							@endforeach
						</select>						
					</div>	
					
					<div class="form-group col-md-4">
						<label for="salary_id">Puesto(*):</label>
						@error('salary_id')
							<span class="text-danger">{{ $message }}</span>
						@enderror
						<select name="salary_id" id="salary_id" class="form-control" required>
							<option value=""> Seleccionar opción </option>
							@foreach($salaries as $salary)			
								<option value="{{ $salary->id }}" {{ old('salary_id') == $salary->id ? 'selected' : '' }}> {{ $salary->puesto }} </option>
							@endforeach
						</select>				
					</div>				
					
				</div>
				
				<div class="form-row">
					<div class="icheck-primary d-inline">
						<input type="checkbox" id="checkboxPrimary3" checked disabled>
						<label for="checkboxPrimary3">
						  Crear número de expediente de 5 digitos automaticamente.
						</label>
					</div>				
				</div>

				<a href="{{ route('employees.index') }}" class="btn btn-default" > <i class="fas fa-times"></i> Cancelar</a>
				<button type="submit" class="btn btn-success">  <i class="fas fa-save"></i> Guardar</button>
				
			</form>

		</div>
	  
	</div>

@endsection