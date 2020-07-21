<?php
set_query_var('ENTRY', 'comments');
get_header();

$term = get_term_by('slug', 'ganagol', 'game');
?>
<div id="comments-matches" class="x-container shadow">
	<div class="row">
		<div class="col-md-12 p-0 mb-3">
			<?php set_query_var('GAME', $term);
			get_template_part('partials/content', 'banner'); ?>
		</div>
		<div class="fluid">
			<?php if(have_posts()): ?>
			<div class="list-posts row">
				<?php $row = 0;
				while(have_posts()):
				the_post();
				?>
				<div class="col-12 <?php if($row != 0) { echo 'col-md-4 mb-3'; } else { echo 'hero mb-4'; } ?>">
					<div class="post">
						<a href="<?php the_permalink() ?>"  style="float:none;" class="link_a"><img src="<?php the_post_thumbnail_url('medium') ?>" alt="<?php the_title() ?>"></a>
						<div class="content">
							<div class="date" style="text-transform: none;"><?php the_field('date'); ?></div>
							<h2><a href="<?php the_permalink() ?>" style="float:none;"><?php the_title() ?></a></h2>
							<div class="excerpt"><?php echo excerpt(35) ?></div>
							<a href="<?php the_permalink() ?>" class="view-more">Ver más <i><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 31.49 31.49" style="enable-background:new 0 0 31.49 31.49;" xml:space="preserve"><path style="fill:#1E201D;" d="M21.205,5.007c-0.429-0.444-1.143-0.444-1.587,0c-0.429,0.429-0.429,1.143,0,1.571l8.047,8.047H1.111C0.492,14.626,0,15.118,0,15.737c0,0.619,0.492,1.127,1.111,1.127h26.554l-8.047,8.032c-0.429,0.444-0.429,1.159,0,1.587c0.444,0.444,1.159,0.444,1.587,0l9.952-9.952c0.444-0.429,0.444-1.143,0-1.571L21.205,5.007z"/></svg></i></a>
						</div>
					</div>
				</div>
				<?php $row++;
				endwhile; ?>
			</div>
			<?php endif; ?>
            <div class="pagination my-4 d-flex justify-content-center">
                <div class="nav mx-1"><?php previous_posts_link('Anterior'); ?></div>
                <div class="nav mx-1"><?php next_posts_link('Siguiente'); ?></div>
            </div>
		</div>
		<div class="col-md-4 mb-3 mb-md-0" style="display: none;">
			<?php dynamic_sidebar('comments-matches-sidebar') ?>
		</div>
	</div>
</div>
<style type="text/css">
.post:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
.post {
    transition: 0.3s;
    border-bottom-left-radius: 7px;
    border-bottom-right-radius: 7px;
}
</style>
<?php get_footer();