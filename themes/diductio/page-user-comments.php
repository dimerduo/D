<?php
    /*
     * Template Name: Активность (комментарии)
     * Данный шаблон выводит страницу активности пользователя (его комментарии)
    */
    get_header();
    if (get_query_var('username')) {
        $user_obj = get_user_by('slug', get_query_var('username'));
        $title    = "Активность " . $user_obj->display_name;
        $user_id  = $user_obj->ID;
    } else {
        $title   = "Моя активность";
        $user_id = get_current_user_id();
    }
    $view_path = Diductio::gi()->settings['view_path'];
    $id        = $user_id ?: get_current_user_id();
    $page      = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $per_page = 10; /* Hardcode */
    $limit     = $per_page;
    $offset    = ($page * $limit) - $limit;


    $args           = array(
        'offset'     => $offset,
        'author__in' => $id,
        'number'     => $limit,
    );
    $total_comments = get_comments(array(
        'orderby'    => 'post_date',
        'order'      => 'DESC',
        'author__in' => $id,
        'status'     => 'approve',
    ));

    $pages         = ceil(count($total_comments) / $per_page);
    $user_comments = get_comments($args);

?>

<div id="primary" class="content-area">
    <?php do_action('page-user-comments-header'); ?>

    <main id="main" class="site-main" role="main">
        <header class="page-header">
            <h1 class="entry-title"><?=$title;?></h1>
        </header>
                <?php
                    // Include the page content template.
                    get_template_part('content', 'user-comments');
                ?>

        <?php
            $args = array(
                'format'    => 'page/%#%',
                'total'     => $pages,
                'current'   => $page,
                'show_all'  => false,
                'end_size'  => 1,
                'mid_size'  => 2,
                'prev_next' => true,
                'prev_text' => __('Previous'),
                'next_text' => __('Next'),
                'type'      => 'plain',
            );
        ?>
        <?php if ($user_comments > $per_page): ?>
            <nav class="navigation pagination custom-page-wrapper" role="navigation">
                <div class="nav-links custom-pagination">
                    <?php echo paginate_links($args); ?>
                </div>
            </nav>
        <?php endif; ?>
    </main>
</div>


<?php get_footer(); ?>
