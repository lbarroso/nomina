<div class="modal fade" id="modal-employee" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">
	
		<div class="modal-content">
		
			<form id="formEmployee">
			
				<div class="modal-header">
					<h4 class="modal-title" id="staticBackdropLabel"> Expediente: <span id="id-expediente"></span> </h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span id="check-status"></span>						
					</button>
				</div>
				
				<div class="modal-body">
				
					<input type="hidden" name="id" id="id">
					
					<div class="form-row">
					
						<div class="form-group col-md-4">
							<label for="nombre">Nombres(*):</label>
							<input type="text" name="nombre" id="nombre" onFocus="this.select()" class="form-control" onkeyup="this.value = this.value.toUpperCase();" required>
						</div>

						<div class="form-group col-md-4">
							<label for="paterno">Apellido paterno(*):</label>
							<input type="text" name="paterno" id="paterno" onFocus="this.select()" class="form-control" onkeyup="this.value = this.value.toUpperCase();" required>
						</div>

						<div class="form-group col-md-4">
							<label for="materno">Apellido materno:</label>
							<input type="text" name="materno" id="materno" onkeyup="this.value = this.value.toUpperCase();" class="form-control" >
						</div>
					
					</div>	

					<div class="form-row">
					
						<div class="form-group col-md-6">
							<label for="rfc">RFC(*):</label>
							<input type="text" name="rfc" id="rfc" class="form-control" pattern="^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$" onkeyup="this.value = this.value.toUpperCase();" title="El RFC debe tener 13 caracteres, sin espacios ni caracteres especiales" required>
							<small class="form-text text-muted">El RFC debe tener 13 caracteres, sin espacios ni caracteres especiales.</small>							
						</div>			

						<div class="form-group col-md-6">
							<label for="curp">CURP(*):</label>							
							<input type="text" name="curp" id="curp" class="form-control" pattern="^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]{2}$" onkeyup="this.value = this.value.toUpperCase();" title="El CURP debe tener 18 caracteres en el formato correcto" required>
						</div>	
						
					</div>

					<div class="form-row">
					
						<div class="form-group col-md-6">
							<label for="nss">NSS(*):</label>
							<input type="text" name="nss" id="nss" class="form-control" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" required>
						</div>	
						
						<div class="form-group col-md-6">
							<label for="nss">Fecha ingreso(*):</label>
							<input type="date" name="fechaIngreso" id="fechaIngreso" class="form-control" required>
						</div>						
					
					</div>
					
					<div class="form-row">
					
						<div class="form-group col-md-6">
							<label for="codigoPostal">Codigo postal:</label>
							<input type="number" name="codigoPostal" maxlength="5" onFocus="this.select()" id="codigoPostal" class="form-control" required>
						</div>	
						
						<div class="form-group col-md-6">
							<label for="infonavit"> Descuento infonavit: </label>
							<input type="number" name="infonavit" id="infonavit" onFocus="this.select()" class="form-control">
						</div>								
					
					</div>	

                    <div class="form-group">
						<label for="salary_id">Puesto:</label>
						<select name="salary_id" id="salary_id" class="form-control">
                            <option value="">Selecciona una opción</option>
                        </select>
				    </div>	
					
				</div>
				
				
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fas fa-times"></i> Cerrar</button>
					<button type="submit" class="btn btn-success"> <i class="fas fa-save"></i> Guardar</button>
				</div>
				
			</form>
			
		</div>
		
	</div>
	
</div>