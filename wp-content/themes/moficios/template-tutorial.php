<?php /* Template Name: Tutorial */
set_query_var('ENTRY', 'tutorial');
$term = get_field('game');
get_header();
?>
<div id="tutorial" class="x-container shadow">
	<div class="row">
        <?php if (!empty($term)){ ?>
        <div class="col-md-12 p-0 ewbanner-">
            <?php set_query_var('GAME', $term);
            get_template_part('partials/content', 'banner'); ?>
        </div>
        <?php } ?>
        <div class="col-12 page-header">
            <h1 class="py-2 py-md-4 mb-0">Tutoriales</h1>
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
            <h2><?php the_title() ?></h2>
            <hr>
        </div>
        <div class="col-12 px-2 px-md-5">
            <?php if (have_rows('slider')): ?>
            <div class="slider">
                <?php while (have_rows('slider')): the_row(); ?>
                <div class="slide">
                    <?php if (get_sub_field('slide_type') == 'image'): ?>
                    <img data-lazy="<?php echo get_sub_field( 'image' )['url'] ?>" alt="<?php echo get_sub_field( 'image' )['alt'] ?>">
                    <?php else: ?>
                    <div class="video-container"><?php the_sub_field('video') ?></div>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="post col-12 px-3 px-md-5">
            <?php if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    the_content();
                }
            } ?>
        </div>
	</div>
</div>
<?php get_footer();