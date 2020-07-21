<?php /* Template Name: Cómo jugar */
set_query_var('ENTRY', 'how_to_play');
$term = get_field('game');
get_header();
$pages = new WP_Query(array(
	'post_type' => 'page',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'meta_key' => 'tutorial_category',
	'meta_value' => 'tutoriales'
));
$pages = $pages->posts;
$tutorial_categories = get_field_object('tutorial_category')['choices'];
?>
<div id="tutorial" class="x-container shadow">
	<div class="row">
		<?php if (!empty($term)){ ?>
        <div class="col-md-12 p-0 ewbanner-">
            <?php set_query_var('GAME', $term);
            get_template_part('partials/content', 'banner'); ?>
        </div>
        <?php } ?>
		<div class="col-12">
			<h1 class="my-4">Tutoriales</h1>
		</div>
		<div class="col-12 px-0 px-md-5 mb-4">
			<?php wp_nav_menu([
				'theme_location'  => 'tutorial-menu',
				'container'       => '',
				'menu_id'         => false,
				'menu_class'      => 'nav',
				'depth'           => 1,
			]); ?>
		</div>
		<div class="col-12 px-3 px-md-5">
			<div class="d-flex align-items-center justify-content-between">
                <h2><?php the_title() ?></h2>
                <ul class="list-categories">
                    <?php $idx = 0; foreach ( $tutorial_categories as $label => $name ): ?>
                    <li <?php if($idx == 0) echo 'class="active"' ?>>
                        <a href="#" data-category="<?php echo $label ?>"><?php echo $name ?></a>
                    </li>
                    <?php $idx++; endforeach; ?>
                </ul>
            </div>
			<hr class="mt-0">
		</div>
		<div class="col-12 px-3 px-md-5 mb-4">
			<div class="list-pages row mt-3 mb-3">
				<?php foreach ($pages as $page):
				if ($page->ID == 229) continue; ?>
                <div class="page col-md-6 mb-4">
                    <img src="<?php echo get_the_post_thumbnail_url( $page->ID ) ?>" alt="<?php echo $page->post_title ?>" class="w-100 rounded mb-3">
                    <div class="px-3 px-md-4">
                        <div class="date" style="text-transform: none;"><span style="text-transform: capitalize;margin-right: 2px;"><?php echo date_i18n('l', strtotime($hero->post_date)) ?></span> <?php echo date_i18n('d', strtotime($hero->post_date)) ?> de <span style="margin-left: 5px;margin-right: 5px;"><?php echo date_i18n('F', strtotime($hero->post_date)) ?></span> de <?php echo date_i18n('Y', strtotime($hero->post_date)) ?></div>
                        <a href="<?php echo get_the_permalink($page->ID) ?>">
                            <h4 class="mb-0"><?php echo $page->post_title ?></h4>
                        </a>
                        <div class="post"><?php echo get_the_excerpt($page->ID) ?></div>
                        <div class="text-right">
                            <a href="<?php echo get_the_permalink($page->ID) ?>" class="view-more">Ver más ➜</a>
                        </div>
                    </div>
                </div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();