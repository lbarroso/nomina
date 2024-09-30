var datatable;

$(document).ready(function(){	

    datatable = $('#employee').DataTable({
		
		order: [[5, 'asc']], 
        ajax: indexUrl,
		
        columns: [
            {
                data:'id',
                render: function(data,type,row){
                    var edit = ''
                    var pDelete = ''                  
                    edit = '<button title="editar" data-id="'+data+'" data-toggle="modal" data-target="#modal-employee" class="btn btn-warning btn-sm mr-1"><i class="fas fa-edit"></i></button>';
				   return edit;
                }
            },		
			{
				data:'status',
                render: function(data, type, row) {
                    if (data == 0) {
                        return '<a href="activate/'+row.id+'" title="clic para activar empleado" > <i class="fas fa-user-times" style="color: red;"></i> <small>inactivo</small> </a>';
                    } else {
                        return '<a href="terminate/'+row.id+'" title="clic para dar de baja"> <i class="fas fa-user-check" style="color: green;"></i> <small>activo</small>  </a>';
                    }
                }
			},			
            { data:'nombre' },            
            { data:'paterno' },
			{ data:'materno' },
            { data:'curp' },
            { data:'rfc' },
			{ data:'expediente' },
			{ data:'salary.puesto' },
            {
                data: 'salary.tab_vig',
                render: function(data, type, row) {
                    if (data) {
                        var salarioDiario = data / 30;
                        return salarioDiario.toFixed(2); // Redondear a 2 decimales
                    }
                    return '0';
                }
            },			
			{ data:'nss' }
        ]
    });
	
	// open modal
    $('#modal-employee').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');

        if(parseInt(id) == 0){
            $("#formEmployee")[0].reset();
            $('#id').val(0)
        }else{
            find(id);
        } 
      
    });
	
    $('#formEmployee').validate({
        rules:{
            codigoPostal: {
                required:true,
				digits: true,
                minlength: 5,
                maxlength: 5			
            },
            rfc: {
                required: true,
                minlength: 13,
                maxlength: 13				
            },
            nombre: {
                required: true,
            },
            paterno: {
                required: true,
            },
            fechaIngreso: {
                required: true,
                date: true
            },			
        },
        messages: {
            codigoPostal: {
                required: "El campo Código Postal es obligatorio.",
                digits: "El Código Postal debe contener solo dígitos.",
                minlength: "El Código Postal debe tener exactamente 5 caracteres.",
                maxlength: "El Código Postal debe tener exactamente 5 caracteres."
            },
            rfc: {
                required: "El campo RFC es obligatorio.",
                minlength: "El RFC debe tener exactamente 13 caracteres.",
                maxlength: "El RFC debe tener exactamente 13 caracteres."				
            },
            nombre: {
                required: "El campo Nombres(*) es obligatorio."
            },
            paterno: {
                required: "El campo Apellido Paterno es obligatorio."
            },
            fechaIngreso: {
                required: "El campo Fecha de Ingreso es obligatorio.",
                date: "Por favor ingresa una fecha válida."
            }			
        },		
        errorClass:'text-danger',
        submitHandler:function(form){
            let id =  $('#id').val()
            let url = storeUrl;
            let method  = 'post';

            if(parseInt(id) !== 0){
                url = updateUrl.replace("/0","/"+id);
                method = 'PUT';
            }

            $.ajax({
                method: method,
                url : url,
                data:$(form).serialize(),
                success:function(){
                    $('#modal-employee').modal('hide');
                    $.notify("Se guardo correctamente", "success");
                    datatable.ajax.reload();
                },
                error:function(res){
                    console.log(res);
                }
            })

        }
    });	
	
	getSalaries();
	
}) // document.ready

// show modal
function find(id){
    let url = showUrl.replace("/0","/"+id);

    $.ajax({
        method:'get',
        url:url,
        success:function(response){
			$('#id-expediente').html(response.expediente);
            $('#nombre').val(response.nombre);
            $('#paterno').val(response.paterno);
            $('#materno').val(response.materno);
            $('#rfc').val(response.rfc);
			$('#curp').val(response.curp);
			$('#nss').val(response.nss);			
            $('#salary_id').val(response.salary_id);
			$('#fechaIngreso').val(response.fechaIngreso);
			$('#codigoPostal').val(response.codigoPostal);
			$('#infonavit').val(response.infonavit);
	
			if(response.status == "1"){
				$('#check-status').html('<div class="alert alert-success"> <i class="fas fa-check-circle"></i>  ACTIVO</div>');
			}else{
				$('#check-status').html('<div class="alert alert-danger"> <i class="fas fa-times-circle"></i> INACTIVO</div>');
			}
			
            $('#id').val(response.id);
        }
    })
}


function getSalaries(){
    $.ajax({
        method: 'get',
        url: urlSalaries,
        success:function(response){
            var salaries = "";

            for (let index = 0; index < response.length; index++) {
                const element = response[index];
                salaries += "<option value = '"+ element['id'] +"' > " + element["puesto"] + "</option>";
            }

            $('#salary_id').append(salaries);
        }
    })
}

/*
			{
				data: 'fechaIngreso',
				render: function(data, type, row) {
					if (data) {
						var date = new Date(data);
						var day = ("0" + date.getDate()).slice(-2);
						var month = ("0" + (date.getMonth() + 1)).slice(-2);
						var year = date.getFullYear();
						return day + "-" + month + "-" + year;
					} else {
						return '';
					}
				}
            },	
*/