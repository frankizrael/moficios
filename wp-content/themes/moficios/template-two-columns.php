<?php /* Template Name: Two columns */
set_query_var('ENTRY', 'columns');
get_header();
?>
<div id="columns" class="x-container shadow">
    <div class="content">
        <div class="page-header">
            <div class="subtitle" style="display: none;"><?php the_field('subtitle') ?></div>
            <h1><?php the_title() ?></h1>
        </div>
        <?php if (have_rows('blocks')): ?>
        <div class="blocks row">
            <?php while (have_rows('blocks')): the_row(); ?>
            <div class="block col-md-6 mb-2 mb-md-4">
                <h2><?php the_sub_field('title') ?></h2>
                <div class="text"><?php the_sub_field('text') ?></div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer();