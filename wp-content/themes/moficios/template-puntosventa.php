<?php /* Template Name: puntosventa */
set_query_var('ENTRY', 'puntosventa');
get_header();

$customer_service = get_field('customer_service');
$location = get_field('map');
?>
<div id="puntosventa" class="x-container shadow listopen">
	<div class="map-overflow ">
		<div class="switchPunts">
			<div class="content_switchPunts">
				<div class="title"><h1><?php the_title(); ?></h1></div>
				<div class="switchIn">
					<div class="switch_select">
						<label for="departament">Departamento</label>
						<select id="departament">
							<option value="00">--Seleccionar</option>
							<option value="01">Amazonas</option>
							<option value="02">Ancash</option>
							<option value="03">Apurímac</option>
							<option value="04">Arequipa</option>
							<option value="05">Ayacucho</option>
							<option value="06">Cajamarca</option>
							<option value="07">Callao</option>
							<option value="08">Cuzco</option>
							<option value="09">Huancavelica</option>
							<option value="10">Huánuco</option>
							<option value="11">Ica</option>
							<option value="12">Junín</option>
							<option value="13">La Libertad</option>
							<option value="14">Lambayeque</option>
							<option value="15">Lima</option>
							<option value="16">Loreto</option>
							<option value="17">Madre de Dios</option>
							<option value="18">Moquegua</option>
							<option value="19">Pasco</option>
							<option value="20">Piura</option>
							<option value="21">Puno</option>
							<option value="22">San Martín</option>
							<option value="23">Tacna</option>
							<option value="24">Tumbes</option>
							<option value="25">Ucayali</option>
						</select>
					</div>
					<div class="switch_select disabled">
						<label for="province">Provincia</label>
						<select id="province"></select>
					</div>
					<div class="switch_select disabled">
						<label for="distrit">Distrito</label>
						<select id="distrit"></select>
					</div>
				</div>
				<div class="switchOut">
					<ul id="output"></ul>					
				</div>
			</div>
		</div>
		<div class="mapGreat">
			<div class="listOptions">
				<ul>
					<li>Mapa</li>
					<li class="active">Lista</li>
				</ul>
			</div>
			<div class="mapG" id="gmap"></div>
		</div>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcBJvjPVyljL0ErfTjP14Y6AINCap-WoU"></script>
<style type="text/css">.mapG button.gm-control-active.gm-fullscreen-control {display: none;}</style>
<script type="text/javascript">
	/*services url*/
	var options_url = 'https://www.intralot.com.pe/lotto-portal/game_tinka_get_combo_section_map_small.html';
	var points_url = 'https://www.intralot.com.pe/lotto-portal/game_tinka_show_map_section_map_small.html';
	var points_by_coords = 'https://m.intralot.com.pe/client_lotocard_show_map.html';
	var url_principal = '<?php echo esc_url( get_template_directory_uri() ); ?>';
</script>
<style type="text/css">
	#puntosventa .map-overflow .switchPunts .content_switchPunts .switchIn .switch_select.disabled {display: block !important;opacity: 0.7;}#puntosventa .map-overflow .mapGreat .mapG .content-my-map .desc .direction {margin-bottom: 0px;}#puntosventa .map-overflow .mapGreat .mapG .content-my-map .desc .reference {display: none;}
	#puntosventa .map-overflow .switchPunts .content_switchPunts .switchIn {opacity: 0; transition: 0.3s;}
	#puntosventa .map-overflow .switchPunts .content_switchPunts .switchIn.opa {opacity: 1;}#puntosventa.listopen .map-overflow .mapGreat { opacity: 0; }#puntosventa.listopen .map-overflow .mapGreat.opa { opacity: 1; } #puntosventa .map-overflow .switchPunts .content_switchPunts .switchOut { opacity: 0; } #puntosventa .map-overflow .switchPunts .content_switchPunts .switchOut.opa { opacity: 1; }
</style>
<script type="text/javascript">
	//complete selects
	jQuery('.listOptions li').on('click',function(){
		let $this = jQuery(this);
		jQuery('#puntosventa').toggleClass('listopen');
		jQuery('.listOptions li').removeClass('active');
		$this.addClass('active');
	});
	//departmant
	jQuery.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'loaddep'
        },
        success: function (resp) {
           jQuery('#departament').html('');
	       jQuery('#departament').append(resp);
        }
    });

	//province
	jQuery('#departament').on('change',function(){
		let val = jQuery('#departament').val();		
		/*jQuery.ajax({
	        url: options_url+'?departmentId='+val+'&metodo=loadProvince',
	        dataType: 'jsonp',
	        success: function (resp) {
	           jQuery('#province').html('');
	           jQuery('#province').append(resp);
	           jQuery('#province').closest('div').removeClass('disabled');
	           jQuery('#distrit').closest('div').addClass('disabled');
	           jQuery('#distrit').html('');
	        }
	    });*/
	    jQuery.ajax({
	        url: '/wp-admin/admin-ajax.php',
	        method: 'get',
	        data: {
	            action: 'loadoptions',
	            method: 'province',
            	dep_id: val,
            	prov_id: 0
	        },
	        success: function (resp) {
	           jQuery('#province').html('');
	           jQuery('#province').append(resp);
	           jQuery('#province').closest('div').removeClass('disabled');
	           jQuery('#distrit').closest('div').addClass('disabled');
	           jQuery('#distrit').html('');
	        }
	    });
	});
	//distrit
	jQuery('#province').on('change',function(){
		let val_departament = jQuery('#departament').val();
		let val = jQuery('#province').val();		
		/*jQuery.ajax({
	        url: options_url+'?departmentId='+val_departament+'&provinceId='+val+'&metodo=loadDistrict',
	        dataType: 'jsonp',
	        success: function (resp) {
	           jQuery('#distrit').html('');
	           jQuery('#distrit').append(resp);
	           jQuery('#distrit').closest('div').removeClass('disabled');
	        }
	    });*/
	    jQuery.ajax({
	        url: '/wp-admin/admin-ajax.php',
	        method: 'get',
	        data: {
	            action: 'loadoptions',
	            method: 'distric',
            	dep_id: val_departament,
            	prov_id: val
	        },
	        success: function (resp) {
	           jQuery('#distrit').html('');
	           jQuery('#distrit').append(resp);
	           jQuery('#distrit').closest('div').removeClass('disabled');
	        }
	    });
	});
	//active filters
	jQuery('#distrit').on('change',function(){
		let val_departament = jQuery('#departament').val();
		let val_province = jQuery('#province').val();
		let val = jQuery('#distrit').val();		
		let data = jQuery('#distrit').attr('data');	
		/*jQuery.ajax({
	        url: points_url+'?departmentId='+val_departament+'&provinceId='+val+'&districtId='+val,
	        dataType: 'jsonp',
	        success: function (resp) {
	        	//send coords map
	           initMap(latlng, resp, 10);
	        }
	    });*/
	    if (data == 1) {
		    jQuery.ajax({
		        url: '/wp-admin/admin-ajax.php',
		        method: 'get',
		        data: {
		            action: 'loadpoints',
	            	dep_id: val_departament,
	            	prov_id: val_province,
	            	dist_id: val
		        },
		        success: function (resp) {
		           //send coords map
		           var json = JSON.parse(resp);
		           initMap(latlng, json, 16);
		        }
		    });
		}
	});	
//geolocation	
	function process_cords(cords){		
		/*jQuery.ajax({
	        url: points_by_coords+'?latitude='+cords.lat+'&length='+cords.lng,
	        dataType: 'jsonp',
	        success: function (resp) {
	           jQuery('#departament').val(resp[0].departmentId);
	           jQuery('#departament').trigger('change');
	           jQuery('#province').val(resp[0].provinceId);
	           jQuery('#province').trigger('change');
		       jQuery('#distrit').val(resp[0].districtId);
		       //jQuery('#distrit').trigger('change');
		       initMap(latlng, resp, 10);
	        }
	    });*/
	    jQuery.ajax({
	        url: '/wp-admin/admin-ajax.php',
	        method: 'get',
	        data: {
	            action: 'callwebservicepoints',
	            lat: cords.lat,
            	lng: cords.lng
	        },
	        success: function (resp) {
		       //jQuery('#distrit').trigger('change');*/
		       var json = JSON.parse(resp);		       
	           jQuery('#departament').val(json[0].departmentId);
	           //providence
	           	jQuery.ajax({
			        url: '/wp-admin/admin-ajax.php',
			        method: 'get',
			        data: {
			            action: 'loadoptions',
			            method: 'province',
		            	dep_id: json[0].departmentId,
		            	prov_id: 0
			        },
			        success: function (resp) {
			           jQuery('#province').html('');
			           jQuery('#province').append(resp);
			           jQuery('#province').closest('div').removeClass('disabled');
			           jQuery('#distrit').closest('div').addClass('disabled');
			           jQuery('#distrit').html('');
			           jQuery('#province').val(json[0].provinceId);
			           //
			           
					    jQuery.ajax({
					        url: '/wp-admin/admin-ajax.php',
					        method: 'get',
					        data: {
					            action: 'loadoptions',
					            method: 'distric',
				            	dep_id: json[0].departmentId,
				            	prov_id: json[0].provinceId
					        },
					        success: function (resp) {
					           jQuery('#distrit').html('');
					           jQuery('#distrit').append(resp);
					           jQuery('#distrit').closest('div').removeClass('disabled');
					           jQuery('#distrit').val(json[0].districtId);
					           setTimeout(function(){
					           	  jQuery('#distrit').attr('data',1);
					           	  jQuery('#puntosventa .map-overflow .switchPunts .content_switchPunts .switchIn').addClass('opa');
					           	  jQuery('#puntosventa.listopen .map-overflow .mapGreat').addClass('opa');
					           	  jQuery('#puntosventa .map-overflow .switchPunts .content_switchPunts .switchOut').addClass('opa');
					           },300);
				       		   		       		   
					        }
					    });
			        }
			    });

		       initMap(latlng, json, 16);
	        }
	    })
	}
//points
	let icono = url_principal+'/src/assets/images/icono-tinka.png';
	let map, infoWindow, posOverride;
	let svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18.42" height="24" viewBox="0 0 18.42 24"><path id="Trazado_322" data-name="Trazado 322" d="M-364.03-69a9.221,9.221,0,0,0-9.21,9.21,9.11,9.11,0,0,0,1.985,5.706L-364.03-45l7.227-9.086a9.11,9.11,0,0,0,1.983-5.7A9.221,9.221,0,0,0-364.03-69Zm0,13.049a3.953,3.953,0,0,1-3.948-3.948,3.952,3.952,0,0,1,3.948-3.948h0a3.952,3.952,0,0,1,3.948,3.948A3.953,3.953,0,0,1-364.03-55.951Z" transform="translate(373.24 69)" fill="#ff3501"/></svg>';
	var markers = [];
	function addMarker(locations){
		jQuery('#output').html('');
		markers=[];
		for (var i = 0; i < locations.length; i++) {  
			if (locations[i].latitude != 'null') {
				var marker = new google.maps.Marker({
				  position: new google.maps.LatLng( parseFloat(locations[i].latitude),  parseFloat(locations[i]["length"])),
				  map: map,
				  icon: icono
				});		
				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {					
						var contenidoG = '<div class="content-my-map"><h3>'+locations[i].luckyPoint+'</h3><div class="desc"><div class="direction">'+locations[i].address+'</div><div class="reference"><strong>Referencia:</strong>'+locations[i].reference+'</div></div></div>';
						infoWindow.setContent(contenidoG);
						infoWindow.open(map, marker);
						var latlong_r = {
							lat: parseFloat(locations[i].latitude),
							lng: parseFloat(locations[i]["length"])
						}
						//centerMap(latlong_r,7);						
					}
				})(marker, i));
				markers.push(marker);

				let template = '<li data-id='+i+' data-lat="'+locations[i].latitude+'" data-long="'+locations[i]["length"]+'"><div class="icon">'+svg+'</div><div class="content-p"><h3>'+locations[i].luckyPoint+'</h3>'+locations[i].address+'</div></li>';
				jQuery('#output').append(template);				
			}
		}
		centerMap(locations[0].latitude, locations[0]["length"] ,16);	
		jQuery('#output li').on('click',function(){
			let $this = jQuery(this);
			let id = $this.attr('data-id');
			let latr = parseFloat($this.attr('data-lat'));
			let long = parseFloat($this.attr('data-long'));
			centerMap(latr,long,18);
			google.maps.event.trigger(markers[id], 'click');
		});
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
	}
	var latlng;
	navigator.geolocation.watchPosition(function(position) {
	   //default init
		latlng = {lat: -12.0641100, lng: -77.070982}; 
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
	    	latlng = {lat: -12.0641100, lng: -77.070982};
			process_cords(latlng);  
	    }
	});
</script>
<?php get_footer();