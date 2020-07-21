<?php
set_query_var('ENTRY', 'single_comment');
get_header();

$term = get_term_by('slug', 'ganagol', 'game');
?>
<div id="single-comments-matches" class="x-container shadow">
	<div class="row">
		<div class="col-md-12 p-0 mb-3">
			<?php set_query_var('GAME', $term);
			get_template_part('partials/content', 'banner'); ?>
		</div>		
		<div class="col-md-12 mb-4">
			<?php the_post_thumbnail('full', array('class' => 'featured')) ?>
			<div class="title"><h1><?php the_title() ?></h1></div>
			<?php if (have_posts()): ?>
			<div class="content">
				<?php while (have_posts()) {
					the_post();
					the_content();
				} ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php get_footer();