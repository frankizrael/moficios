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

/* Register sidebar */
register_sidebar(array(
	'name' => 'Footer',
	'id' => 'footer-sidebar',
	'before_widget' => '<div id="%1$s" class="col-12 col-md mb-3 mb-md-0 widget %2$s">',
	'after_widget'  => '</div>',
));

register_sidebar(array(
	'name' => 'Últimos resultados',
	'id' => 'last-result-sidebar',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
    'before_title'  => '<h4 class="widgettitle">',
	'after_title'   => "</h4>\n",
));

register_sidebar(array(
	'name' => 'Comentarios partidos',
	'id' => 'comments-matches-sidebar',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h4 class="widgettitle">',
	'after_title'   => "</h4>\n",
));

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


/*loadPoints*/
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

/* Get last result pagination */
function get_last_result_paged() {
	$term_id = filter_input(INPUT_GET, 'term_id');
	$paged = filter_input(INPUT_GET, 'paged');
	$last_results = get_last_results($term_id, 5, $paged);
	foreach ($last_results->posts as $last_result): ?>
    <div class="last-result d-flex align-items-center jsResult">
        <img src="<?php echo get_the_post_thumbnail_url($last_result->ID, 'full') ?>" alt="<?php echo $last_result->post_title ?>" class="rounded shadow jsVideoEmisor" style="width: auto;">
        <div class="content">
            <div class="date"><span style="text-transform: none;"><?php echo the_field('fecha',$last_result->ID) ?></div>
            <div class="content-bassis"><?php echo wpautop($last_result->post_content, true) ?></div>
        </div>
        <a href="javascript:void(0)" class="view-more jsVideoEmisor">➜</a>
        <div class="last-result-iframe-video jsVideoReceptor" style="display: none" data-iframe='<?php echo the_field('video',$last_result->ID) ?>' data-date='<?php echo the_field('fecha',$last_result->ID) ?>' data-title="<?php echo $last_result->post_title ?>" data-description="<?php echo $last_result->post_content ?>"></div>
    </div>
    <?php if ($last_result != end($last_results->posts)): ?>
    <hr class="my-3">
    <?php endif; endforeach;
    wp_die();
}
add_action('wp_ajax_nopriv_last_results', 'get_last_result_paged');
add_action('wp_ajax_last_results', 'get_last_result_paged');

/* all last result filter */
function get_all_result_p() {
	$term_id = $_GET['term_id'];
	$title = $_GET['title'];
	$last_results = get_all_results($title, $term_id, -1, -1);
	if (!empty($last_results->posts)) {
	foreach ($last_results->posts as $last_result): ?>
	<li>
		<div class="content-list-result jsResult">
			<div class="content-list-imagen">
				<a href="javascript:void(0)" class="jsVideoEmisor">
				<img src="<?php echo get_the_post_thumbnail_url($last_result->ID, 'full') ?>" alt="<?php echo $last_result->post_title ?>" class="rounded shadow" style="width: auto;"></a>
			</div>
			<div class="content-list-text">
				<div class="contentRight">
                    <h2><a href="javascript:void(0)" class="jsVideoEmisor"><?php echo $last_result->post_title ?></a></h2>
                </div>
				<div class="jsVideoReceptor" style="display: none" data-iframe='<?php echo the_field('video',$last_result->ID) ?>' data-date='<?php echo the_field('fecha',$last_result->ID) ?>' data-title="<?php echo $last_result->post_title ?>" data-description="<?php echo $last_result->post_content ?>"></div> 
			</div>
		</div>
	</li>
	<?php
	endforeach;
	} else {
		?>
		<li class="notdata">No se encontraron resultados, prueba de nuevo</li>
		<?php
	}
	wp_die();
}
add_action('wp_ajax_all_result', 'get_all_result_p');
add_action('wp_ajax_nopriv_all_result', 'get_all_result_p');

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

/* Get filter in how to play */
function get_pages_how_to_play() {
	$filter_term = filter_input(INPUT_GET, 'category');
    $pages = new WP_Query(array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_key' => 'tutorial_category',
        'meta_value' => $filter_term
    ));
    $pages = $pages->posts;
    foreach ($pages as $page):
    if ($page->ID == 314 || $page->ID == 824 || $page->ID == 821 || $page->ID == 826) continue; ?>
    <div class="page col-md-6 mb-4">
        <img src="<?php echo get_the_post_thumbnail_url( $page->ID ) ?>" alt="<?php echo $page->post_title ?>" class="w-100 rounded mb-3">
        <div class="px-3 px-md-4">
            <div class="date" style="text-transform: none;"><span><?php echo date_i18n('l', strtotime($hero->post_date)) ?></span> <?php echo date_i18n('d', strtotime($hero->post_date)) ?> de <span style="margin-left: 5px;margin-right: 5px;"><?php echo date_i18n('F', strtotime($hero->post_date)) ?></span> de <?php echo date_i18n('Y', strtotime($hero->post_date)) ?></div>
            <a href="<?php echo get_the_permalink($page->ID) ?>">
                <h4 class="mb-0"><?php echo $page->post_title ?></h4>
            </a>
            <div class="post"><?php echo get_the_excerpt($page->ID) ?></div>
            <div class="text-right">
                <a href="<?php echo get_the_permalink($page->ID) ?>" class="view-more">Ver más <i><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 31.49 31.49" style="enable-background:new 0 0 31.49 31.49;" xml:space="preserve"><path style="fill:#1E201D;" d="M21.205,5.007c-0.429-0.444-1.143-0.444-1.587,0c-0.429,0.429-0.429,1.143,0,1.571l8.047,8.047H1.111C0.492,14.626,0,15.118,0,15.737c0,0.619,0.492,1.127,1.111,1.127h26.554l-8.047,8.032c-0.429,0.444-0.429,1.159,0,1.587c0.444,0.444,1.159,0.444,1.587,0l9.952-9.952c0.444-0.429,0.444-1.143,0-1.571L21.205,5.007z"/></svg></i></a>
            </div>
        </div>
    </div>
    <?php endforeach;
	wp_die();
}
add_action('wp_ajax_nopriv_how_to_play', 'get_pages_how_to_play');
add_action('wp_ajax_how_to_play', 'get_pages_how_to_play');

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