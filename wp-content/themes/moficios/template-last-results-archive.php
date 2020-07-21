<?php
/**
 * Template Name: Últimos resultados
 */
set_query_var('ENTRY', 'last_results');
get_header();

$term = get_field('game');
$paged = 1;

$last_results = get_last_results($term->term_id, 6, $paged);
$hero = array_shift($last_results->posts);
?>
<div id="last-results" class="x-container shadow">
    <div class="row">
        <div class="col-md-12 p-0 mb-4 mb-md-3">
            <?php set_query_var('GAME', $term);
            get_template_part('partials/content', 'banner'); ?>
        </div>
    </div>
    <div class="last-container">
        <div class="video-step">
            <div class="hero-last-result">
                <div class="title">
                    <h1>Sorteos anteriores</h1>
                </div>
                <div class="videoJs w-100 rounded shadow">
                    <?php echo the_field('video',$hero->ID) ?>
                </div>
                <div class="px-2">
                    <div class="date dateJs" style="text-transform: none;"><span style="text-transform: capitalize;margin-right: 2px;"><?php echo the_field('fecha',$hero->ID) ?></span></div>
                    <h2 class="title mb-3 titleJs" style="display: none;"><?php echo $hero->post_title ?></h2>
                    <div class="d-flex justify-content-between flex-column flex-md-row">
                        <div class="content contentJs"><?php echo wpautop($hero->post_content, true) ?></div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row buttons my-4 my-md-3 align-items-stretch">
                <?php if(have_rows('buttons')):
                while (have_rows('buttons')): the_row(); ?>
                <div class="col-12 col-md mb-2 mb-md-0">
                    <a href="<?php the_sub_field( 'button_link' ) ?>" class="btn btn-block" style="background-color: <?php the_sub_field('button_color') ?>; color: <?php the_sub_field('button_color_text') ?>" >
                        <?php the_sub_field('button_text') ?>
                    </a>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
        <div class="list-step">
            <!-- search -->
            <div class="search-main">
                <div class="search-input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14.515" height="15.034" viewBox="0 0 14.515 15.034"><path id="_149852" data-name="149852" d="M15.278,13.694,11.7,9.972A6.068,6.068,0,1,0,7.054,12.14a6,6,0,0,0,3.478-1.1l3.605,3.75a.792.792,0,1,0,1.141-1.1ZM7.054,1.583A4.487,4.487,0,1,1,2.567,6.07,4.492,4.492,0,0,1,7.054,1.583Z" transform="translate(-0.984)"/></svg>
                    <input type="text" id="search" placeholder="Buscar sorteo por fecha" data-term="<?php echo $term->term_id ?>">
                </div>
                <div class="search-list">
                    <ul id="post-list"></ul>
                </div>
            </div>

            <b>Anteriores sorteos</b>
            <?php if (count($last_results->posts) > 0): ?>
            <div class="last-results-list">
                <?php foreach ($last_results->posts as $last_result): ?>
                <div class="last-result d-flex align-items-center jsResult">
                    <img src="<?php echo get_the_post_thumbnail_url($last_result->ID, 'thumbnail') ?>" alt="<?php echo $last_result->post_title ?>" class="rounded shadow">
                    <div class="content">
                        <div class="date" style="text-transform: none;"><?php echo the_field('fecha',$last_result->ID) ?></div>
                        <div class="content-bassis"><?php echo $last_result->post_content; ?></div>
                    </div>
                    <a href="javascript:void(0)" class="view-more jsVideoEmisor">➜</a>
                    <div class="last-result-iframe-video jsVideoReceptor" style="display: none" data-iframe='<?php echo the_field('video',$last_result->ID) ?>' data-date='<?php echo the_field('fecha',$last_result->ID) ?>' data-title="<?php echo $last_result->post_title ?>" data-description="<?php echo $last_result->post_content ?>"></div>
                </div>
                <?php if ($last_result != end($last_results->posts)): ?>
                <hr class="my-3">
                <?php endif; endforeach; ?>
            </div>
            <?php else: ?>
            <small class="d-block">No se encontraron resultados.</small>
            <?php endif; ?>
            <div class="pagination">
                <a href="#" class="btn btn-light mr-2 prev-pagination disabled" data-term="<?php echo $term->term_id ?>">‹</a>
                <div class="current btn border"><small><span id="paged"><?php echo $paged; ?></span></small></div>
                <a href="#" class="btn btn-light mx-2 next-pagination <?php if ($paged == $last_results->max_num_pages) echo 'disabled' ?>" data-term="<?php echo $term->term_id ?>">›</a>
                <div class="total-pages"><small>de <span id="max_results"><?php echo $last_results->max_num_pages ?></span></small></div>
            </div>
        </div>
    </div>
</div>
<?php get_footer();