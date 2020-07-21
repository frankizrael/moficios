import '../scss/puntosventa.scss';
//complete selects
	jQuery('.listOptions li').on('click',function(){
		let $this = jQuery(this);
		jQuery('#puntosventa').toggleClass('listopen');
		jQuery('.listOptions li').removeClass('active');
		$this.addClass('active');
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
		/*jQuery.ajax({
	        url: points_url+'?departmentId='+val_departament+'&provinceId='+val+'&districtId='+val,
	        dataType: 'jsonp',
	        success: function (resp) {
	        	//send coords map
	           initMap(latlng, resp, 10);
	        }
	    });*/
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
			        }
			    });
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
			        }
			    });

		       initMap(latlng, json, 16);
	        }
	    })
	}
	var latlng;
	if (navigator.geolocation) {
		//default init
		latlng = {lat: -12.0641100, lng: -77.070982}; 
		//default end
		navigator.geolocation.getCurrentPosition(function(position) {
			latlng = {
				lat:	position.coords.latitude
				,lng:	position.coords.longitude
			};
			process_cords(latlng);
		});		
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
						var contenidoG = '<div class="content-my-map"><h3>'+locations[i].luckyPoint+'</h3><div class="desc"><div class="direction">'+locations[i].address+'</div><strong>Referencia:</strong>'+locations[i].reference+'</div></div>';
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