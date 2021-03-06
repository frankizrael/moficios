<?php

// Register Custom Post Type
function register_cpt_comments() {
	$labels = array(
		'name'                  => _x('comments', 'Post Type General Name', 'miloficios'),
		'singular_name'         => _x('comment', 'Post Type Singular Name', 'miloficios'),
		'menu_name'             => __('comments', 'miloficios'),
		'name_admin_bar'        => __('comment', 'miloficios'),
		'all_items'             => __('Todos los comments', 'miloficios'),
		'add_new_item'          => __('Añadir comment', 'miloficios'),
		'search_items'          => __('Buscar comment', 'miloficios'),
	);
	$rewrite = array(
		'slug'                  => 'comments',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => false,
	);
	$args = array(
		'label'                 => __('comment', 'miloficios'),
		'description'           => __('comments', 'miloficios'),
		'labels'                => $labels,
		'supports'              => array('title'),
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
	register_post_type('comments', $args);
}

add_action('init', 'register_cpt_comments', 0);