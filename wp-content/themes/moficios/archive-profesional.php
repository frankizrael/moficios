<?php
if (is_ajax()):
	get_template_part('partials/content', 'promotions');
else:
    set_query_var('ENTRY', 'promotions');
    get_header();
    $categories = get_terms(array(
        'taxonomy' => 'category',
        'exclude' => 1
    ));
?>
    <div id="promotions" class="x-container shadow ">
        <div class="page-header">
            <div class="col-left">
                <h1><?php the_archive_title() ?></h1>
            </div>
            <div class="col-right">
                <div class="scroll-wrapper h-100">
                    <div class="filters">
                        <a href="#" class="active" data-cat="">Todo</a>
		                <?php foreach ($categories as $cat): ?>
                            <a href="#" data-cat="<?php echo $cat->term_id ?>"><?php echo $cat->name ?></a>
		                <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mt-0">
        <div class="content">
            <?php get_template_part('partials/content', 'promotions') ?>
        </div>
    </div>
<?php
    get_footer();
endif;