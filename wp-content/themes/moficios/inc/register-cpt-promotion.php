<?php

// Register Custom Post Type
function register_cpt_profesionales() {
	$labels = array(
		'name'                  => _x('Profesionales', 'Post Type General Name', 'miloficios'),
		'singular_name'         => _x('Profesional', 'Post Type Singular Name', 'miloficios'),
		'menu_name'             => __('Profesionales', 'miloficios'),
		'name_admin_bar'        => __('Profesional', 'miloficios'),
		'all_items'             => __('Todos los profesionales', 'miloficios'),
		'add_new_item'          => __('Añadir profesional', 'miloficios'),
		'search_items'          => __('Buscar profesional', 'miloficios'),
	);
	$rewrite = array(
		'slug'                  => 'profesionales',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => false,
	);
	$args = array(
		'label'                 => __('Profesional', 'miloficios'),
		'description'           => __('Profesionales', 'miloficios'),
		'labels'                => $labels,
		'supports'              => array('title', 'thumbnail'),
		'taxonomies'            => array('category'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-star-filled',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'post',
		'show_in_rest'          => false,
	);
	register_post_type('profesional', $args);
}

add_action('init', 'register_cpt_profesionales', 0);