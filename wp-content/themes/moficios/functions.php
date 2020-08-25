<?php
/* Theme Support */
add_theme_support('html-5');
add_theme_support('post-thumbnails');
add_theme_support('custom-logo');
add_theme_support('title-tag');
add_filter('show_admin_bar', '__return_false');

/* Register custom post types and custom taxonomies */
require_once 'inc/register-taxonomy-game.php';
require_once 'inc/register-cpt-last-results.php';
require_once 'inc/register-cpt-banner.php';
require_once 'inc/register-cpt-promotion.php';
require_once 'inc/register-cpt-comments.php';

/* Bootstrap Nav Walker */
require_once 'inc/bootstrap-nav-walker.php';

/* Register Widgets */
require_once 'inc/register-button-widget.php';

/* Register menus */
function register_my_menus() {
	register_nav_menus(
		array(
			'header-menu' => __('Header Menu'),
            'tutorial-menu' => __('Tutorial Menu')
		)
	);
}
add_action('init', 'register_my_menus');

/* Hide posts from menu */
function hide_post_menu() {
    remove_menu_page('edit.php');
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'hide_post_menu');

/* Load assets */
function load_assets($entries) {
	$assets = file_get_contents(get_stylesheet_directory() . '/assets.json');
	$assets = json_decode($assets);
	foreach ( $assets as $chunk => $files ) {
		foreach ($entries as $entry) {
			if ( $chunk == $entry ) {
				foreach ($files as $type => $asset) {
					switch ($type) {
						case 'js':
							wp_enqueue_script($chunk, get_stylesheet_directory_uri() . '/dist/' . $asset, array(), false, true);
							break;
						case 'css':
							wp_enqueue_style($chunk, get_stylesheet_directory_uri() . '/dist/' . $asset);
					}
				}
			}
		}
	}
}

function acf_load_n_departamento_field_choices( $field ) {  
    $field['required'] = true;
    $field['choices'] = array();
    // if has rows
    $ubigeo = get_field('s_ubigeo','options');
    foreach ($ubigeo as $ub) {
    	$value = $ub['departamento'];
        $label = $ub['departamento'];
        $field['choices'][ $value ] = $label;   
    }    
    return $field;
}
add_filter('acf/load_field/name=n_departamento', 'acf_load_n_departamento_field_choices');


function acf_load_n_provincia_field_choices( $field ) {  
    $field['required'] = true;
    $field['choices'] = array();
    // if has rows
    $ubigeo = get_field('s_ubigeo','options');
    foreach ($ubigeo as $ub) {
    	$value = $ub['departamento'];
    	$provincias = array();
        foreach ($ub['provincias'] as $prov) {
        	$text = $prov['text'];
         	$provincias[$text] = $text;
        }  
       	$field['choices'][ $value ] = $provincias; 
    }    
    return $field;
}
add_filter('acf/load_field/name=n_provincia', 'acf_load_n_provincia_field_choices');

function acf_load_n_distrito_field_choices( $field ) {  
    $field['required'] = true;
    $field['choices'] = array();
    // if has rows
    $ubigeo = get_field('s_ubigeo','options');
    foreach ($ubigeo as $ub) {
        foreach ($ub['provincias'] as $prov) {
        	$text = $prov['text'];
    		$distritos = array();
         	foreach ($prov['distrito'] as $dist) {
         		$textd = $dist['text'];
         		$distritos[$textd] = $textd;
         	}
       		$field['choices'][ $text ] = $distritos; 
        }  
    }    
    return $field;
}
add_filter('acf/load_field/name=n_distrito', 'acf_load_n_distrito_field_choices');


if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}

/* Set custom logo class */
function custom_logo_class($html) {
	$html = str_replace('custom-logo-link', 'navbar-brand', $html);
	return $html;
}
add_filter('get_custom_logo', 'custom_logo_class', 10);


/* Get last results */
function get_last_results($term_id, $posts=5, $paged=1) {
	return new WP_Query(array(
		'post_type' => 'last_results',
		'posts_per_page' => $posts,
		'order' => 'DESC',
		'orderby' => 'date',
		'paged' => $paged,
		'tax_query' => array(
			array(
				'taxonomy' => 'game',
				'field' => 'id',
				'terms' => $term_id
			)
		)
	));
}

/* Get all results */
function get_all_results($title, $term_id, $posts=5, $paged=1) {
	return new WP_Query(array(
		'post_type' => 'last_results',
		'posts_per_page' => $posts,
		'order' => 'DESC',
		'orderby' => 'date',
		's' => $title,
		'paged' => $paged,
		'tax_query' => array(
			array(
				'taxonomy' => 'game',
				'field' => 'id',
				'terms' => $term_id
			)
		)
	));
}


/* callWebService */
function get_callWebServicePoints() {
	$lat = filter_input(INPUT_GET, 'lat');
	$lng = filter_input(INPUT_GET, 'lng');
	$args = array(
	  'numberposts' => -1,
	  'post_type'   => 'profesional'
	);	 
	$profesionals = get_posts( $args );
	$resp = array();
	foreach ($profesionals as $pp) {
		$id = $pp->ID;
		$term_obj_list = get_the_terms( $pp->ID, 'category' );
		$terms_name = array();	
		$listerm;	
		$a = 0;
		foreach ($term_obj_list as $tt) {
			array_push($terms_name,$tt->name);			
			if ($a == 0) {
				$listerm = $tt->name;
			} else {
				$listerm = $listerm.', '.$tt->name;
			}
			$a++;
		}
		$adress = get_field('n_departamento',$id).', '.get_field('n_provincia',$id).', '.get_field('n_distrito',$id);
		$link = get_permalink($id);
		$data = array (
		    "id"  			=> $id,
		    "dni" 			=> $pp->post_title,
		    "nombre"		=> get_field('nombre',$id),
		    "apellido"		=> get_field('apellido',$id),
		    "address"		=> $adress,
		    "departamento"	=> get_field('n_departamento',$id),
		    "provincia"		=> get_field('n_provincia',$id),
		    "distrito"		=> get_field('n_distrito',$id),
		    "reference"		=> get_field('direccion',$id),
		    "latitud"		=> get_field('latitud',$id),
		    "longitud"		=> get_field('longitud',$id),
		    "category"		=> $terms_name,
		    "categoryHtml"	=> $listerm,
		    "link"			=> $link,
		);		
		$datajson = $data;
		array_push($resp,$datajson);
	}	
	echo json_encode($resp);
	wp_die();
}
add_action('wp_ajax_nopriv_callwebservicepoints', 'get_callWebServicePoints');
add_action('wp_ajax_callwebservicepoints', 'get_callWebServicePoints');

/*loadDepartaments*/
function get_loadDep() {
	$departamento = filter_input(INPUT_GET, 'departamento');
	$args = array(
	  'numberposts' => -1,
	  'post_type'   => 'profesional'
	);	 
	$profesionals = get_posts( $args );
	$resp = array();
	foreach ($profesionals as $pp) {
		$id = $pp->ID;
		if (sanitize_title(get_field('n_departamento',$id)) == $departamento) {
			$term_obj_list = get_the_terms( $pp->ID, 'category' );
			$terms_name = array();	
			$listerm;	
			$a = 0;
			foreach ($term_obj_list as $tt) {
				array_push($terms_name,$tt->name);			
				if ($a == 0) {
					$listerm = $tt->name;
				} else {
					$listerm = $listerm.', '.$tt->name;
				}
				$a++;
			}
			$adress = get_field('n_departamento',$id).', '.get_field('n_provincia',$id).', '.get_field('n_distrito',$id);
			$link = get_permalink($id);
			$data = array (
			    "id"  			=> $id,
			    "dni" 			=> $pp->post_title,
			    "nombre"		=> get_field('nombre',$id),
			    "apellido"		=> get_field('apellido',$id),
			    "address"		=> $adress,
			    "departamento"	=> get_field('n_departamento',$id),
			    "provincia"		=> get_field('n_provincia',$id),
			    "distrito"		=> get_field('n_distrito',$id),
			    "reference"		=> get_field('direccion',$id),
			    "latitud"		=> get_field('latitud',$id),
			    "longitud"		=> get_field('longitud',$id),
			    "category"		=> $terms_name,
			    "categoryHtml"	=> $listerm,
		    	"link"			=> $link,
			);		
			$datajson = $data;
			array_push($resp,$datajson);
		}
	}
	$ubigeo = get_field('s_ubigeo','options');
	$provincias_r = array();
    foreach ($ubigeo as $ub) {
    	if (sanitize_title($ub['departamento']) == $departamento) {
	        foreach ($ub['provincias'] as $prov) {
	        	$arraycontent = array(
	        		'value' => sanitize_title($prov['text']),
	        		'text'	=> $prov['text']
	        	);
	        	array_push($provincias_r,$arraycontent);
	        } 
    	} 
    }
	echo json_encode($resp).'|'.json_encode($provincias_r);
	wp_die();
}
add_action('wp_ajax_nopriv_loaddep', 'get_loadDep');
add_action('wp_ajax_loaddep', 'get_loadDep');

/*loadProvince*/
function get_loadProv() {
	$departamento = filter_input(INPUT_GET, 'departamento');
	$provincia = filter_input(INPUT_GET, 'provincia');
	$args = array(
	  'numberposts' => -1,
	  'post_type'   => 'profesional'
	);	 
	$profesionals = get_posts( $args );
	$resp = array();
	foreach ($profesionals as $pp) {
		$id = $pp->ID;
		if (sanitize_title(get_field('n_provincia',$id)) == $provincia) {
			$term_obj_list = get_the_terms( $pp->ID, 'category' );
			$terms_name = array();	
			$listerm;	
			$a = 0;
			foreach ($term_obj_list as $tt) {
				array_push($terms_name,$tt->name);			
				if ($a == 0) {
					$listerm = $tt->name;
				} else {
					$listerm = $listerm.', '.$tt->name;
				}
				$a++;
			}
			$adress = get_field('n_departamento',$id).', '.get_field('n_provincia',$id).', '.get_field('n_distrito',$id);
			$link = get_permalink($id);
			$data = array (
			    "id"  			=> $id,
			    "dni" 			=> $pp->post_title,
			    "nombre"		=> get_field('nombre',$id),
			    "apellido"		=> get_field('apellido',$id),
			    "address"		=> $adress,
			    "departamento"	=> get_field('n_departamento',$id),
			    "provincia"		=> get_field('n_provincia',$id),
			    "distrito"		=> get_field('n_distrito',$id),
			    "reference"		=> get_field('direccion',$id),
			    "latitud"		=> get_field('latitud',$id),
			    "longitud"		=> get_field('longitud',$id),
			    "category"		=> $terms_name,
			    "categoryHtml"	=> $listerm,
		    	"link"			=> $link,
			);		
			$datajson = $data;
			array_push($resp,$datajson);
		}
	}
	$ubigeo = get_field('s_ubigeo','options');
	$distritos_r = array();
    foreach ($ubigeo as $ub) {
    	if (sanitize_title($ub['departamento']) == $departamento) {
	        foreach ($ub['provincias'] as $prov) {
	        	if (sanitize_title($prov['text']) == $provincia) {
	        		foreach ($prov['distrito'] as $dist) {
			        	$arraycontent = array(
			        		'value' => sanitize_title($dist['text']),
			        		'text'	=> $dist['text']
			        	);
			        	array_push($distritos_r,$arraycontent);
			        }
		        }
	        } 
    	} 
    }
	echo json_encode($resp).'|'.json_encode($distritos_r);
	wp_die();
}
add_action('wp_ajax_nopriv_loadprov', 'get_loadProv');
add_action('wp_ajax_loadprov', 'get_loadProv');


/*loadPoints*/
function get_loadDist() {
	$distrito = filter_input(INPUT_GET, 'distrito');
	$args = array(
	  'numberposts' => -1,
	  'post_type'   => 'profesional'
	);	 
	$profesionals = get_posts( $args );
	$resp = array();
	foreach ($profesionals as $pp) {
		$id = $pp->ID;
		if (sanitize_title(get_field('n_distrito',$id)) == $distrito) {
			$term_obj_list = get_the_terms( $pp->ID, 'category' );
			$terms_name = array();	
			$listerm;	
			$a = 0;
			foreach ($term_obj_list as $tt) {
				array_push($terms_name,$tt->name);			
				if ($a == 0) {
					$listerm = $tt->name;
				} else {
					$listerm = $listerm.', '.$tt->name;
				}
				$a++;
			}
			$adress = get_field('n_departamento',$id).', '.get_field('n_provincia',$id).', '.get_field('n_distrito',$id);
			$link = get_permalink($id);
			$data = array (
			    "id"  			=> $id,
			    "dni" 			=> $pp->post_title,
			    "nombre"		=> get_field('nombre',$id),
			    "apellido"		=> get_field('apellido',$id),
			    "address"		=> $adress,
			    "departamento"	=> get_field('n_departamento',$id),
			    "provincia"		=> get_field('n_provincia',$id),
			    "distrito"		=> get_field('n_distrito',$id),
			    "reference"		=> get_field('direccion',$id),
			    "latitud"		=> get_field('latitud',$id),
			    "longitud"		=> get_field('longitud',$id),
			    "category"		=> $terms_name,
			    "categoryHtml"	=> $listerm,
		    	"link"			=> $link,			    
			);		
			$datajson = $data;
			array_push($resp,$datajson);
		}
	}
	echo json_encode($resp);
	wp_die();
}
add_action('wp_ajax_nopriv_loaddist', 'get_loadDist');
add_action('wp_ajax_loaddist', 'get_loadDist');

/*loadPoints*/
function get_loadOficios() {
	$departamento = filter_input(INPUT_GET, 'departamento');
	$provincia = filter_input(INPUT_GET, 'provincia');
	$distrito = filter_input(INPUT_GET, 'distrito');
	$guias = filter_input(INPUT_GET, 'guias');



	$args = array(
	  'numberposts' => -1,
	  'post_type'   => 'profesional'
	);	 
	$profesionals = get_posts( $args );
	$resp = array();
	foreach ($profesionals as $pp) {
		$id = $pp->ID;
		if (sanitize_title(get_field('n_departamento',$id)) == $departamento && sanitize_title(get_field('n_provincia',$id)) == $provincia && sanitize_title(get_field('n_distrito',$id)) == $distrito) {
			$term_obj_list = get_the_terms( $pp->ID, 'category' );
			$terms_name = array();	
			$listerm;	
			$a = 0;
			foreach ($term_obj_list as $tt) {
				array_push($terms_name,sanitize_title($tt->name));			
				if ($a == 0) {
					$listerm = $tt->name;
				} else {
					$listerm = $listerm.', '.$tt->name;
				}
				$a++;
			}
			if (in_array($guias, $terms_name)) {
				$adress = get_field('n_departamento',$id).', '.get_field('n_provincia',$id).', '.get_field('n_distrito',$id);
				$link = get_permalink($id);
				$data = array (
				    "id"  			=> $id,
				    "dni" 			=> $pp->post_title,
				    "nombre"		=> get_field('nombre',$id),
				    "apellido"		=> get_field('apellido',$id),
				    "address"		=> $adress,
				    "departamento"	=> get_field('n_departamento',$id),
				    "provincia"		=> get_field('n_provincia',$id),
				    "distrito"		=> get_field('n_distrito',$id),
				    "reference"		=> get_field('direccion',$id),
				    "latitud"		=> get_field('latitud',$id),
				    "longitud"		=> get_field('longitud',$id),
				    "category"		=> $terms_name,
				    "categoryHtml"	=> $listerm,
		    		"link"			=> $link				    
				);		
				$datajson = $data;
				array_push($resp,$datajson);
			}
		}
	}
	echo json_encode($resp);
	wp_die();
}
add_action('wp_ajax_nopriv_loadoficios', 'get_loadOficios');
add_action('wp_ajax_loadoficios', 'get_loadOficios');

/*loadPoints*/
function get_loadPeople() {
	$departamento = filter_input(INPUT_GET, 'departamento');
	$provincia = filter_input(INPUT_GET, 'provincia');
	$distrito = filter_input(INPUT_GET, 'distrito');
	$guias = filter_input(INPUT_GET, 'guias');

	$args = array(
	  'numberposts' => -1,
	  'post_type'   => 'profesional'
	);	 
	$profesionals = get_posts( $args );
	$resp = array();
	$b = 0;
	foreach ($profesionals as $pp) {
		$id = $pp->ID;
		if (sanitize_title(get_field('n_departamento',$id)) == $departamento && sanitize_title(get_field('n_provincia',$id)) == $provincia && sanitize_title(get_field('n_distrito',$id)) == $distrito) {
			$term_obj_list = get_the_terms( $pp->ID, 'category' );
			$terms_name = array();	
			$listerm;	
			$a = 0;
			foreach ($term_obj_list as $tt) {
				array_push($terms_name,sanitize_title($tt->name));			
				if ($a == 0) {
					$listerm = $tt->name;
				} else {
					$listerm = $listerm.', '.$tt->name;
				}
				$a++;
			}
			if (in_array($guias, $terms_name)) {
				$adress = get_field('n_departamento',$id).', '.get_field('n_provincia',$id).', '.get_field('n_distrito',$id);
				$link = get_permalink($id);		
				?>
				<div class="resultado-autor">
					<div class="res-profile">
						<div class="img-cliente">
							<img src="<?php echo get_the_post_thumbnail_url($id); ?>" alt="">
							<div class="info-trabajador">
								<h5><?php echo get_field('nombre',$id).' '.get_field('apellido',$id); ?></h5>
								<?php
									$valoracion = get_field('valoracion',$id);								
								?>
								<div class="calificacion">
									<?php
										for ($i=1; $i < 6; $i++) { 
											$active = '';
											if ($i <= $valoracion) {												
												$active = '-active';
											}
										?>
										<img class="star-active" src="<?php echo get_template_directory_uri(); ?>/img/star-solid<?php echo $active;?>.svg" alt="">										
											<?php
										}
									?>
								</div>
								<div class="disponible-fat">
									<p class="disponible">DISPONIBLE</p>
								</div>
							</div>
						</div>
					</div>
					<div class="res-especialidad">
						<h3>Especialidad</h3>
						<div class="especialidad">
							<p><?php echo $listerm; ?></p>
							<a href="<?php echo $link; ?>">Ver mas</a>
						</div>
					</div>
				</div>
				<?php
				$b++;
			}
		}
	}
	$re = '<div id="crss" style="display:none;" data="'.$b.'"></div>';
	echo $re;
	wp_die();
}
add_action('wp_ajax_nopriv_loadpeople', 'get_loadPeople');
add_action('wp_ajax_loadpeople', 'get_loadPeople');


/*add valorait*/
function get_addValoration() {
	$comment = filter_input(INPUT_GET, 'comment');
	$valoration = filter_input(INPUT_GET, 'valoration');
	$id_user = filter_input(INPUT_GET, 'id_user');
	$id_pro = filter_input(INPUT_GET, 'id_pro');
	$nameuser = filter_input(INPUT_GET, 'nameuser');
	$namepro = filter_input(INPUT_GET, 'namepro');

	$date = date( 'Y-m-d ');	
	//add
	$title = $date.' Comentario de '.$nameuser.' sobre '.$namepro;
	$args = array(
        'post_title' => $title,
        'post_type' => 'comments',
        'post_status' => 'publish'
    );
    $commend_id = wp_insert_post($args);
	//recalculate
    update_field( 'usuario', $id_user, $commend_id );
    update_field( 'profesional', $id_pro, $commend_id );
    update_field( 'calification', $valoration, $commend_id );
    update_field( 'date', $date, $commend_id );
	update_field( 'comentario', $comment, $commend_id );
	//change value
	$valoracion = get_field('valoracion',$id_pro);
	$valoracionespersona = get_field('valoracionespersona',$id_pro);

	$newvaloracionespersona = $valoracionespersona+1;
	update_field( 'valoracionespersona', $newvaloracionespersona, $id_pro );

	$newvaloracion = ($valoracion*$valoracionespersona + $valoration)/$newvaloracionespersona;
	$notdecimal = round($newvaloracion); 
	update_field( 'valoracion', $notdecimal, $id_pro );	

	//echo $notdecimal;

	wp_die();
}
add_action('wp_ajax_nopriv_addvaloration', 'get_addValoration');
add_action('wp_ajax_addvaloration', 'get_addValoration');

/**/


/* Remove prefix */
function remove_archive_prefix($title) {
    return preg_replace('/^\w+: /', '', $title);
}
add_filter('get_the_archive_title', 'remove_archive_prefix');

/* Excerpt size */
function tn_custom_excerpt_length($length) {
	return 20;
}
add_filter('excerpt_length', 'tn_custom_excerpt_length', 999);

/* ajaxlogin */
function get_loginprofesional() {
	$user = filter_input(INPUT_GET, 'user');
	$pass = filter_input(INPUT_GET, 'pass');
	$args = array(
	  'numberposts' => -1,
	  'post_type'   => 'profesional'
	);	 
	$profesionals = get_posts( $args );
	$user_asig = 0;
	$pass_asig = 0;
	$idprod = 0;

	foreach ($profesionals as $pro) {
		if (get_the_title($pro->ID) == $user) {
			$user_asig = 1;	
			if (get_field('password', $pro->ID) == $pass) {
				$pass_asig = 1;	
				$idprod = $pro->ID;
			}
		} 
	}	
	$term_obj_list = get_the_terms( $idprod, 'category' );
	$terms_name = array();	
	foreach ($term_obj_list as $tt) {
		array_push($terms_name,$tt->name);
	}
	if ($user_asig == 1) {
		if ($pass_asig == 1) {
			$msj = array(
				'acess' 				=> 1,
				'key' 					=> 'ACCESS','msj'=>'Acceso autorizado',
				'id'					=> $idprod,
				'nombre' 				=> get_field('nombre',$idprod),
				'apellido' 				=> get_field('apellido',$idprod),
				'dni' 					=> get_the_title($idprod),
				'imagen' 				=> get_the_post_thumbnail_url($idprod),
				'direccion' 			=> get_field('direccion',$idprod),
				'correo' 				=> get_field('correo',$idprod),
				'telefono' 				=> get_field('telefono',$idprod),
				'fecha_nacimiento' 		=> get_field('fecha_nacimiento',$idprod),
				'cualidades' 			=> get_field('cualidades',$idprod),
				'antecedentes_penales' 	=> get_field('antecedentes_penales',$idprod),
				'carnet_sanidad' 		=> get_field('carnet_sanidad',$idprod),
				'categories'			=> $terms_name,
				'trabajos'				=> get_field('trabajos',$idprod)
			);
		} else {
			$msj = array('acess' => 0,'key'=>'ERROR_PASS','msj'=>'Password incorrecto');			
		}
	} else {
		$msj = array('acess' => 0,'key'=>'ERROR_USER','msj'=>'Usuario no encontrado');		
	}
	echo json_encode($msj);
	wp_die();
}
add_action('wp_ajax_nopriv_loginprofesional', 'get_loginprofesional');
add_action('wp_ajax_loginprofesional', 'get_loginprofesional');


/*register*/

function get_registerprofesional() {
		
	$register_user = filter_input(INPUT_GET, 'register_user');
	$register_name = filter_input(INPUT_GET, 'register_name');
	$register_lastname = filter_input(INPUT_GET, 'register_lastname');
	$register_email = filter_input(INPUT_GET, 'register_email');
	$register_telefono = filter_input(INPUT_GET, 'register_telefono');
	$password = filter_input(INPUT_GET, 'password');
	$insideuser = filter_input(INPUT_GET, 'insideuser');


	$args_s = array(
	  'numberposts' => -1,
	  'post_type'   => 'profesional'
	);
	$profesionals = get_posts( $args_s );
	$user_asig = 1;
	$email_asig = 1;
	foreach ($profesionals as $pro) {
		if (get_the_title($pro->ID) == $register_user) {
			$user_asig = 0;	
			if (get_field('register_email', $pro->ID) == $register_email) {
				$email_asig = 0;	
			}
		} 
	}

	if ($user_asig == 1) {
		if ($email_asig == 1) {
			$title = $register_user;
			$args = array(
		        'post_title' => $title,
		        'post_type' => 'profesional',
		        'post_status' => 'publish'
		    );
		    $profesional_id = wp_insert_post($args);

		    update_field( 'nombre', $register_name, $profesional_id );
			update_field( 'apellido', $register_lastname, $profesional_id );
			update_field( 'password', $password, $profesional_id );
			update_field( 'correo', $register_email, $profesional_id );
			update_field( 'telefono', $register_telefono, $profesional_id );

			$term_obj_list = get_the_terms( $profesional_id, 'category' );
			$terms_name = array();	
			foreach ($term_obj_list as $tt) {
				array_push($terms_name,$tt->name);
			}

			$msj = array(
				'acess' 				=> 1,
				'key' 					=> 'ACCESS','msj'=>'Acceso autorizado',
				'id'					=> $profesional_id,
				'nombre' 				=> get_field('nombre',$profesional_id),
				'apellido' 				=> get_field('apellido',$profesional_id),
				'dni' 					=> get_the_title($profesional_id),
				'imagen' 				=> get_the_post_thumbnail_url($profesional_id),
				'direccion' 			=> get_field('direccion',$profesional_id),
				'correo' 				=> get_field('correo',$profesional_id),
				'telefono' 				=> get_field('telefono',$profesional_id),
				'fecha_nacimiento' 		=> get_field('fecha_nacimiento',$profesional_id),
				'cualidades' 			=> get_field('cualidades',$profesional_id),
				'antecedentes_penales' 	=> get_field('antecedentes_penales',$profesional_id),
				'carnet_sanidad' 		=> get_field('carnet_sanidad',$profesional_id),
				'categories'			=> $terms_name,
				'trabajos'				=> get_field('trabajos',$profesional_id)
			);
		} else {
			$msj = array('acess' => 0,'key'=>'ERROR_PASS','msj'=>'Correo ya registrado');			
		}
	} else {
		$msj = array('acess' => 0,'key'=>'ERROR_USER','msj'=>'Usuario ya registrado');		
	}
	echo json_encode($msj);
	wp_die();
}
add_action('wp_ajax_nopriv_registerprofesional', 'get_registerprofesional');
add_action('wp_ajax_registerprofesional', 'get_registerprofesional');

/*add valorait*/
function get_changedatafirst() {
	$user_id = filter_input(INPUT_GET, 'user_id');
	$nombre = filter_input(INPUT_GET, 'nombre');
	$apellido = filter_input(INPUT_GET, 'apellido');
	$direction = filter_input(INPUT_GET, 'direction');
	$correo = filter_input(INPUT_GET, 'correo');
	$telefono = filter_input(INPUT_GET, 'telefono');
	$datenac = filter_input(INPUT_GET, 'datenac');


	//recalculate
	update_field( 'nombre', $nombre, $user_id );
	update_field( 'apellido', $apellido, $user_id );
	update_field( 'direccion', $direction, $user_id );
	update_field( 'correo', $correo, $user_id );
	update_field( 'telefono', $telefono, $user_id );
    update_field( 'fecha_nacimiento', $datenac, $user_id );	

    echo get_field('fecha_nacimiento',$user_id);
	wp_die();
}
add_action('wp_ajax_nopriv_changedatafirst', 'get_changedatafirst');
add_action('wp_ajax_changedatafirst', 'get_changedatafirst');



function get_changeimagenprof() {
	/*$user_id = filter_input(INPUT_GET, 'user_id');
	$file = filter_input(INPUT_GET, 'file');
	$filename = basename($file);
*/
	/*$filename = basename($file);
	$upload_file = wp_upload_bits($filename, null, file_get_contents($file));
	if (!$upload_file['error']) {
		$wp_filetype = wp_check_filetype($filename, null );
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_parent' => $user_id,
			'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
			'post_content' => '',
			'post_status' => 'inherit'
		);
		$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $user_id );
		if (!is_wp_error($attachment_id)) {
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
			wp_update_attachment_metadata( $attachment_id,  $attachment_data );
		}
	}*/
	//echo 
	/*
	$wp_filetype = wp_check_filetype( $getImageFile, null );
	$attachment_data = array(
	    'post_mime_type' => $wp_filetype['type'],
	    'post_title' => sanitize_file_name( $getImageFile ),
	    'post_content' => '',
	    'post_status' => 'inherit'
	);
	$attach_id = wp_insert_attachment( $attachment_data, $getImageFile, $user_id );*/
	/*$wp_filetype = wp_check_filetype($filename, null );
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_parent' => $user_id,
		'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
		'post_content' => '',
		'post_status' => 'inherit'
	);
	$attachment_id = wp_insert_attachment( $attachment, $upload_file, $parent_post_id );
	if (!is_wp_error($attachment_id)) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
		wp_update_attachment_metadata( $attachment_id,  $attachment_data );
	}

	echo get_the_post_thumbnail_url($user_id);*/
	wp_die();
}
add_action('wp_ajax_nopriv_changeimagenprof', 'get_changeimagenprof');
add_action('wp_ajax_changeimagenprof', 'get_changeimagenprof');


function file_upload() {
    $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
    $user_id = $_POST["user"];
    if (in_array($_FILES['file']['type'], $arr_img_ext)) {
        $upload_file = wp_upload_bits($_FILES["file"]["name"], null, file_get_contents($_FILES["file"]["tmp_name"]));
        if (!$upload_file['error']) {
			$attachment = array(
				'post_mime_type' => $_FILES['file']['type'],
				'post_parent' => $user_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', $_FILES["file"]["name"]),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $user_id );
			if (!is_wp_error($attachment_id)) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );
				//add thmbnail
				set_post_thumbnail( $user_id, $attachment_id );
			}
		}     
    }
    echo get_the_post_thumbnail_url($user_id);
    wp_die();
}
add_action( 'wp_ajax_file_upload', 'file_upload' );
add_action( 'wp_ajax_nopriv_file_upload', 'file_upload' );



function addcategoryfi() {
	$user_id = filter_input(INPUT_GET, 'user_id');
	$valor = filter_input(INPUT_GET, 'valor');


	$term_obj_list = get_the_terms( $user_id, 'category' );
	$terms_id = array();	
	foreach ($term_obj_list as $tt) {
		array_push($terms_id,$tt->term_id);
	}
	array_push($terms_id,$valor);

	wp_set_post_categories( $user_id, $terms_id );

	$term_obj_list2 = get_the_terms( $user_id, 'category' );
	$terms_name = array();	
	foreach ($term_obj_list2 as $tt) {
		array_push($terms_name,$tt->name);
	}

	echo json_encode($terms_name);

	

	wp_die();
}
add_action( 'wp_ajax_addcategoryfi', 'addcategoryfi' );
add_action( 'wp_ajax_nopriv_addcategoryfi', 'addcategoryfi' );


function edittextarea() {
	$user_id = filter_input(INPUT_GET, 'user_id');
	$valor = filter_input(INPUT_GET, 'valor');

	update_field('cualidades', $valor, $user_id );
	echo get_field('cualidades',$user_id);
	wp_die();
}
add_action( 'wp_ajax_edittextarea', 'edittextarea' );
add_action( 'wp_ajax_nopriv_edittextarea', 'edittextarea' );


function array_file_upload() {
    $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'application/pdf');
    $user_id = $_POST["user"];
    $atrib = $_POST["atrib"];
    if (in_array($_FILES['file']['type'], $arr_img_ext)) {
        $upload_file = wp_upload_bits($_FILES["file"]["name"], null, file_get_contents($_FILES["file"]["tmp_name"]));
        if (!$upload_file['error']) {
			$attachment = array(
				'post_mime_type' => $_FILES['file']['type'],
				'post_parent' => $user_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', $_FILES["file"]["name"]),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $user_id );
			if (!is_wp_error($attachment_id)) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );
				//add thmbnail
				if ($atrib == 'registro') {
					update_field('antecedentes_penales', $upload_file['url'], $user_id );	
					echo get_field('antecedentes_penales',$user_id);
				}
				if ($atrib == 'carnet') {
					update_field('carnet_sanidad', $upload_file['url'], $user_id );	
					echo get_field('carnet_sanidad',$user_id);
				}
			}
		}
    }    
    wp_die();
}
add_action( 'wp_ajax_array_file_upload', 'array_file_upload' );
add_action( 'wp_ajax_nopriv_array_file_upload', 'array_file_upload' );

function pippin_get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
        return $attachment[0]; 
}

function job_update() {
    $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
    $user_id = $_POST["user"];
    if (in_array($_FILES['file']['type'], $arr_img_ext)) {
        $upload_file = wp_upload_bits($_FILES["file"]["name"], null, file_get_contents($_FILES["file"]["tmp_name"]));
        if (!$upload_file['error']) {
			$attachment = array(
				'post_mime_type' => $_FILES['file']['type'],
				'post_parent' => $user_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', $_FILES["file"]["name"]),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $user_id );
			if (!is_wp_error($attachment_id)) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );



				//add thmbnail				
				// Get the current value.
				$jobs = get_field('field_5f15c73657b8a',$user_id);
				$arrayJobs = array();
				foreach ($jobs as $job) {
					array_push($arrayJobs, array('field_5f15c74457b8b' => pippin_get_image_id($job['imagen'])));
				}
				array_push($arrayJobs, array('field_5f15c74457b8b' => $attachment_id));
				// Update with new value.				
				update_field('field_5f15c73657b8a', $arrayJobs, $user_id);
				/*$jobsnew = get_field('field_5f15c73657b8a',$user_id);
				for ($a=0;$a<count($jobsnew);$a++) {
					update_sub_field( array('field_5f15c73657b8a', $a, 'imagen'), $arrayJobs[$a]['imagen'], $user_id );	
				}*/
				//update_sub_field( array('trabajos', $countplus, 'imagen'), $upload_file['file'], $user_id );
			}
		}     
    }    
    echo json_encode(get_field('trabajos',$user_id));
    wp_die();
}
add_action( 'wp_ajax_job_update', 'job_update' );
add_action( 'wp_ajax_nopriv_job_update', 'job_update' );



/* Reduce terms to names */
function reduce_to_names($term) {
    return $term->name;
}

/* Is Ajax request */
function is_ajax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/* Change posts per page */
function change_posts_per_page( $query ) {
	if (!is_admin() && is_post_type_archive('comments-matches')) {
		$query->set('posts_per_page', '6');
	}
}
add_action('pre_get_posts', 'change_posts_per_page');

/* Google Api Key */
function google_api_key() {
	acf_update_setting('google_api_key', 'AIzaSyCcBJvjPVyljL0ErfTjP14Y6AINCap-WoU');
}
add_action('acf/init', 'google_api_key');

/* get add faq valoration positive */
function get_sendpositivevaloration() {
	$order = $_GET['order'];
	$value = $_GET['value'];
	update_sub_field( array('field_5cddd3b70f116', $order, 'field_5d0baddf36381'), $value, 196 );
	wp_die();
}
add_action('wp_ajax_nopriv_sendpositivevaloration', 'get_sendpositivevaloration');
add_action('wp_ajax_sendpositivevaloration', 'get_sendpositivevaloration');

/* get add faq valoration positive */
function get_sendnegativevaloration() {
	$order = $_GET['order'];
	$value = $_GET['value'];
	update_sub_field( array('field_5cddd3b70f116', $order, 'field_5d0bb35f7ff40'), $value, 196 );
	wp_die();
}
add_action('wp_ajax_nopriv_sendnegativevaloration', 'get_sendnegativevaloration');
add_action('wp_ajax_sendnegativevaloration', 'get_sendnegativevaloration');


$url_intralot = "http://190.12.81.36/p/";
add_action('init', 'my_session_start', 1);
function my_session_start() {
    if( ! session_id() ) {
        session_start();
    }
}

function redirect_from_visa() {
    $message = filter_input(INPUT_GET, 'message');
    $message = iconv('Windows-1252', 'utf-8', urldecode($message));
	?>
	<body>
	    <script>
	    localStorage.setItem("message", '<?php echo $message; ?>');
	    var redirect = localStorage.getItem("redirect");
	    window.location.href = redirect ? redirect : "/";
	</script>
	</body>
	<?php wp_die();
}
add_action('wp_ajax_nopriv_redirect_visanet', 'redirect_from_visa');
add_action('wp_ajax_redirect_visanet', 'redirect_from_visa');

function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}