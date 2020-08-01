import '../scss/homee.scss';
import Swiper from "swiper";
import Swal from 'sweetalert2';



var swiper = new Swiper('.solicitados .swiper-container', {
  slidesPerView: 6,
  loop: true,
  navigation: {
    nextEl: '.solicitados .swiper-button-next',
    prevEl: '.solicitados .swiper-button-prev',
  },
  breakpoints: {
    1400: {
        slidesPerView: 6,
    },
    800: {
        slidesPerView: 3,
    },
    0: {
        slidesPerView: 2,
    }
  }
});
var swiper = new Swiper('.clientes .swiper-container', {
  slidesPerView: 3,
  spaceBetween: 10,
  loop: true,
  pagination: {
    el: '.clientes .swiper-pagination',
    clickable: true,
  },
  breakpoints: {
    1400: {
        slidesPerView: 3,
    },
    800: {
        slidesPerView: 2,
    },
    0: {
        slidesPerView: 1,
    }
  }
});


//jsHome
let icono = url_principal+'/img/pin_1.png';
let map, infoWindow, posOverride;
var markers = [];
function process_cords(cords){
	console.log(cords);
	$.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'callwebservicepoints',
            lat: cords.lat,
        	lng: cords.lng
        },
        success: function (resp) {
        	var json = JSON.parse(resp);
        	initMap(latlng, json, 16);
        }
    });
}
function addMarker(locations){
	markers=[];
	for (var i = 0; i < locations.length; i++) {  
		if (locations[i].latitud != 'null') {
			var marker = new google.maps.Marker({
			  position: new google.maps.LatLng( parseFloat(locations[i].latitud),  parseFloat(locations[i].longitud) ),
			  map: map,
			  icon: icono
			});		
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {					
					var contenidoG = '<div class="content-my-map"><h3>'+locations[i].nombre+' '+locations[i].apellido+'</h3><div class="desc"><div class="categorias_s">Maestro Especialista : '+locations[i].categoryHtml+'</div><div class="reference"><strong>Dirección: </strong>'+locations[i].reference+'</div><div class="direction">'+locations[i].address+'</div><div class="mylink"><a href="'+locations[i].link+'">Ver más</a></div></div></div>';
					infoWindow.setContent(contenidoG);
					infoWindow.open(map, marker);
					var latlong_r = {
						lat: parseFloat(locations[i].latitud),
						lng: parseFloat(locations[i].longitud)
					}
					centerMap(latlong_r,12);						
				}
			})(marker, i));
			markers.push(marker);		
		}
	}
	//centerMap(parseFloat(locations[0].latitud), parseFloat(locations[0].longitud) ,16);	
}
function centerMap(lat, lng, zoomR){
	map.setZoom(zoomR); 
	map.setCenter(new google.maps.LatLng(lat, lng));
}
function initMap(latlng, array_locations, zoomR) {
	map = new google.maps.Map(document.getElementById('gmap'), {
		center:	latlng,
		scrollwheel: true,
		zoom: zoomR	        
	});					
	infoWindow = new google.maps.InfoWindow({map: map});
	addMarker(array_locations);	
	var icono_user = url_principal+'/img/user_1.png';
	var marker_user = new google.maps.Marker({
	  position: new google.maps.LatLng( latlng.lat, latlng.lng ),
	  map: map,
	  icon: icono_user
	});	
}
var latlng;
navigator.geolocation.watchPosition(function(position) {
   //default init
	latlng = {lat: -11.9052079, lng: -77.054236}; 
	//default end
	latlng = {
		lat:	position.coords.latitude
		,lng:	position.coords.longitude
	};
	process_cords(latlng);
},
function(error) {
    if (error.code == error.PERMISSION_DENIED) {
    	console.log("you denied me :-(");	 
    	latlng = {lat: -11.9011119, lng: -77.051116};
		process_cords(latlng);  
    }
});

function includeSelect($id,list,text){
  $id.html('');
  $id.html('<option disabled selected>'+text+'</option>')
  for (let u=0;u<list.length;u++){
  	let tmpl = '<option value="'+list[u].value+'">'+list[u].text+'</option>';
    $id.append(tmpl);
  }
}

$('#departamento').on('change',function(){
	let val = $(this).val();
	$.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'loaddep',
            departamento: val
        },
        success: function (resp) {
        	var json = JSON.parse(resp.split('|')[0]);
        	var provincias = JSON.parse(resp.split('|')[1]);    
        	includeSelect($('#provincia'),provincias,'Escoger provincia');
        	if (json.length == 0 ) {
        		Swal.fire('Error', 'No se encontraron profesionales en esa área', 'error');
        	} else {
	        	initMap(latlng, json, 16);
	        	centerMap(json[0].latitud,json[0].longitud,16);    	
        	}
        }
    });
});

$('#provincia').on('change',function(){
	let val_dep = $('#departamento').val();
	let val = $('#provincia').val();
	$.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'loadprov',
            departamento: val_dep,
            provincia: val
        },
        success: function (resp) {
        	var json = JSON.parse(resp.split('|')[0]);
        	var distritos = JSON.parse(resp.split('|')[1]); 	
        	includeSelect($('#distrito'),distritos,'Escoger distrito');
        	if (json.length == 0 ) {
        		Swal.fire('Error', 'No se encontraron profesionales en esa área', 'error');
        	} else {
	        	initMap(latlng, json, 16);
	        	centerMap(json[0].latitud,json[0].longitud,16);    	
        	}
        }
    });
});

$('#distrito').on('change',function(){
	let val = $('#distrito').val();
	$.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'loaddist',
            distrito: val
        },
        success: function (resp) {
        	var json = JSON.parse(resp);  	
        	if (json.length == 0 ) {
        		Swal.fire('Error', 'No se encontraron profesionales en esa área', 'error');
        	} else {
	        	initMap(latlng, json, 16);
	        	centerMap(json[0].latitud,json[0].longitud,16);    	
        	}
        }
    });
});

$('#oficio').on('change',function(){
	let val_dep = $('#departamento').val();
	let val_prov = $('#provincia').val();
	let val_dist = $('#distrito').val();
	let val = $('#oficio').val();
	$.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'loadoficios',
            departamento: val_dep,
            provincia: val_prov,
            distrito: val_dist,
            guias: val
        },
        success: function (resp) {
        	var json = JSON.parse(resp);  	
        	if (json.length == 0 ) {
        		Swal.fire('Error', 'No se encontraron profesionales en esa área', 'error');
        	} else {
	        	initMap(latlng, json, 16);
	        	centerMap(json[0].latitud,json[0].longitud,16);   	        	
				$('#sendform').removeClass('disabled');
        	}
        }
    });
});

$('#sendform').on('click',function(){
	let val_dep = $('#departamento').val();
	let val_prov = $('#provincia').val();
	let val_dist = $('#distrito').val();
	let val = $('#oficio').val();
	$.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'loadpeople',
            departamento: val_dep,
            provincia: val_prov,
            distrito: val_dist,
            guias: val
        },
        success: function (resp) {	
        	$('#resultado').html('');
	        $('#resultado').html(resp);	
        	let con = $('#crss').attr('data');
	        $('#count').html(con); 
        	if (con == 0 ) {
        		Swal.fire('Error', 'No se encontraron profesionales en esa área', 'error');
        	} else {
	        	$('.first-file').hide();
				$('.second-file').show();
				$('.f_tit').hide();
        	}
        }
    });
});

