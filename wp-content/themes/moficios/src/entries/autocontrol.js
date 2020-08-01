import '../scss/autocontrol.scss';
import Swal from 'sweetalert2';

$('.autocontrol__tab ul li').on('click',function(){
	let $this = $(this);
	let data = $this.attr('data');
	$('.autocontrol__tab ul li').removeClass('active');
	$this.addClass('active');
	$('.tab_con').hide();
	$(data).show();
});
$('.autocontrol__tab ul li').eq(0).trigger('click');

$('#jsSend').on('click',function(){
	let user = $('#user').val();
	let pass = $('#password').val();	
	
	$.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'loginprofesional',
            user: user,
        	pass: pass
        },
        success: function (resp) {
        	var json = JSON.parse(resp);
        	if (json.acess == '0') {
        		if (json.key == 'ERROR_USER') {
        			$('#user').val('');
        			$('#password').val('');	
        		}
        		if (json.key == 'ERROR_PASS') {
        			$('#password').val('');	        			
        		}
        		$('.errorJsMsj').html(json.msj);
        	}
        	if (json.acess == '1') {    
        		console.log(json);    		
        		let acesspp = json.msj;
        		let welcomemsj = 'Bienvenido '+json.nombre;
        		Swal.fire(acesspp,welcomemsj, 'success');
        		$('#login').hide();
        		$('#data').show();
        		//includedata
        		$('.docJS').html(json.dni);
        		$('.docJS').attr('user_id',json.id);
        		$('#nombreJs').val(json.nombre);
        		$('#apellidoJs').val(json.apellido);
        		$('#directionJs').val(json.direccion);
        		$('#correoJs').val(json.correo);
        		$('#telefonoJs').val(json.telefono);
        		$('#datenacJs').val(json.fecha_nacimiento);
        		$('#imagenJs').attr('src',json.imagen);
        		$('#cualidadesJs').val(json.cualidades);
        		$('#registroJs').attr('href',json.antecedentes_penales);
				$('#carnetJs').attr('href',json.carnet_sanidad);
				//include categories
				for (let i=0;i<json.categories.length;i++){
					$('#jsEspecialidades').append('<li>'+json.categories[i]+'</li>');
					for (let v=0; v<$('#oficio option').length; v++) {
						let html = $('#oficio option').eq(v).text();
						if (html == json.categories[i]) {
							//console.log(html);
							$('#oficio option').eq(v).attr('disabled',true);
						}
					}
				}
				//includeworks
				for (let i=0;i<json.trabajos.length;i++){
					$('#jsTrabajos').append('<li><img src="'+json.trabajos[i].imagen+'"></li>');					
				}				
        	}
        }
    });
});

$('#editFirstData').on('click',function(){
	let nombreJs = $('#nombreJs').val();
	let apellidoJs = $('#apellidoJs').val();
	let directionJs = $('#directionJs').val();
	let correoJs = $('#correoJs').val();
	let telefonoJs = $('#telefonoJs').val();
	let datenacJs = $('#datenacJs').val();
	let user_id = $('.docJS').attr('user_id');
	$.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'changedatafirst',
            user_id: user_id,
            nombre: nombreJs,
        	apellido: apellidoJs,
        	direction: directionJs,
        	correo: correoJs,
        	telefono: telefonoJs,
			datenac: datenacJs
        },
        success: function (resp) {
        	console.log(resp);
        	Swal.fire('Datos actualizados','', 'success');
        }
    });
});

$('#imagenInput').on('change',function(){
	let user_id = $('.docJS').attr('user_id');
	let $this = $(this);
    let file_data = $(this).prop('files')[0];
    let form_data = new FormData();
    form_data.append('file', file_data);
	form_data.append('user', user_id);
    form_data.append('action', 'file_upload');
    $.ajax({
        url: aw.ajaxurl,
        type: 'POST',
        contentType: false,
        processData: false,
        data: form_data,
        success: function (resp) {
        	let url = resp;    
        	$('#imagenJs').attr('src',url);
        	Swal.fire('Datos actualizados','', 'success');
        }
    });
});

$('.editEspecialidades').on('click',function(){
	$('#oficio').show();
	$('.editEspecialidades').addClass('opaa');
});

$('#oficio').on('change',function(){
	let user_id = $('.docJS').attr('user_id');
	let $this = $(this);
    let val = $this.val();
    $.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'addcategoryfi',
            user_id: user_id,
            valor: val
        },
        success: function (resp) {
        	var json = JSON.parse(resp);
        	console.log(json);
        	$('#oficio').val('');
        	$('#oficio').hide();
			$('.editEspecialidades').removeClass('opaa');
			$('#oficio option').attr('disabled',false);
			$('#jsEspecialidades').html('');
			for (let i=0;i<json.length;i++){
				$('#jsEspecialidades').append('<li>'+json[i]+'</li>');
				for (let v=0; v<$('#oficio option').length; v++) {
					let html = $('#oficio option').eq(v).text();
					if (html == json[i]) {
						//console.log(html);
						$('#oficio option').eq(v).attr('disabled',true);
					}
				}
			}
        	Swal.fire('Datos actualizados','', 'success');
        }
    });
});

$('.editTextarea').on('click',function(){
	let user_id = $('.docJS').attr('user_id');
	let $this = $('#cualidadesJs');
    let val = $this.val();
    $.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'edittextarea',
            user_id: user_id,
            valor: val
        },
        success: function (resp) {  
			console.log(resp);        		
        	Swal.fire('Datos actualizados','', 'success');
        }
    });
});

$('#InputregistroJs').on('change',function(){
	let user_id = $('.docJS').attr('user_id');
	let $this = $(this);
    let file_data = $(this).prop('files')[0];
    let form_data = new FormData();
    form_data.append('file', file_data);
	form_data.append('user', user_id);
	form_data.append('atrib', 'registro');
    form_data.append('action', 'array_file_upload');
    $.ajax({
        url: aw.ajaxurl,
        type: 'POST',
        contentType: false,
        processData: false,
        data: form_data,
        success: function (resp) {
        	console.log(resp);   
        	$('#registroJs').attr('href',resp);				
        	Swal.fire('Datos actualizados','', 'success');
        }
    });
});


$('#InputcarnetJs').on('change',function(){
	let user_id = $('.docJS').attr('user_id');
	let $this = $(this);
    let file_data = $(this).prop('files')[0];
    let form_data = new FormData();
    form_data.append('file', file_data);
	form_data.append('user', user_id);
	form_data.append('atrib', 'carnet');
    form_data.append('action', 'array_file_upload');
    $.ajax({
        url: aw.ajaxurl,
        type: 'POST',
        contentType: false,
        processData: false,
        data: form_data,
        success: function (resp) {
        	console.log(resp);   
        	$('#carnetJs').attr('href',resp);
        	Swal.fire('Datos actualizados','', 'success');
        }
    });
});

$('#sendTrabajo').on('change',function(){
	let user_id = $('.docJS').attr('user_id');
	let $this = $(this);
    let file_data = $(this).prop('files')[0];
    let form_data = new FormData();
    form_data.append('file', file_data);
	form_data.append('user', user_id);
    form_data.append('action', 'job_update');
    $.ajax({
        url: aw.ajaxurl,
        type: 'POST',
        contentType: false,
        processData: false,
        data: form_data,
        success: function (resp) {
        	var json = JSON.parse(resp);
        	$('#jsTrabajos').html('');
        	for (let i=0;i<json.length;i++){
				$('#jsTrabajos').append('<li><img src="'+json[i].imagen+'"></li>');					
			}
        	Swal.fire('Datos actualizados','', 'success');
        }
    });
});


