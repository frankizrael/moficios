<?php

// Register Custom Taxonomy
function register_taxonomy_game() {
	$labels = array(
		'name'                       => _x('Juegos', 'Taxonomy General Name', 'intralot'),
		'singular_name'              => _x('Juego', 'Taxonomy Singular Name', 'intralot'),
		'menu_name'                  => __('Juegos', 'intralot'),
		'all_items'                  => __('Todos los juegos', 'intralot'),
		'new_item_name'              => __('Nuevo juego', 'intralot'),
		'add_new_item'               => __('Añadir nuevo juego', 'intralot'),
		'edit_item'                  => __('Editar juego', 'intralot'),
		'update_item'                => __('Actualizar juego', 'intralot'),
		'view_item'                  => __('Ver juego', 'intralot'),
		'add_or_remove_items'        => __('Añadir o eliminar juegos', 'intralot'),
		'search_items'               => __('Buscar juegos', 'intralot'),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => false,
		'show_in_rest'               => false,
	);
	register_taxonomy('game', array('last_results'), $args);
}

add_action('init', 'register_taxonomy_game', 0);