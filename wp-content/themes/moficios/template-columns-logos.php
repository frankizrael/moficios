<?php /* Template Name: Two columns with logos */
set_query_var('ENTRY', 'two_columns');
get_header();
?>
<div id="two-columns" class="x-container shadow">
	<div class="content">
		<div class="row">
			<?php if (have_rows('columns')):
			while(have_rows('columns')): the_row(); ?>
			<div class="col-md-6 mb-3">
				<div class="subtitle" style="display: none;"><?php the_sub_field('subtitle') ?></div>
				<h2 class="title"><?php the_sub_field('title') ?></h2>
				<div class="text"><?php the_sub_field('content') ?></div>
			</div>
			<?php endwhile; endif; ?>
		</div>
		<div class="row align-items-center justify-content-center mt-4 list-logos">
			<?php if (have_rows('logos')):
			while(have_rows('logos')): the_row(); ?>
			<div class="col-6 col-md-3 mb-3">
				<img src="<?php echo get_sub_field( 'image' )['url'] ?>" alt="<?php echo get_sub_field( 'image' )['alt'] ?>">
			</div>
			<?php endwhile; endif; ?>
		</div>
	</div>
</div>
<?php get_footer();