<?php
    /**
     * The template for displaying archive pages
     * Used to display archive-type pages if nothing more specific matches a query.
     * For example, puts together date-based pages if no date.php file exists.
     * If you'd like to further customize these archive views, you may create a
     * new template file for each one. For example, tag.php (Tag archives),
     * category.php (Category archives), author.php (Author archives), etc.
     *
     * @link       https://codex.wordpress.org/Template_Hierarchy
     * @package    WordPress
     * @subpackage Twenty_Fifteen
     * @since      Twenty Fifteen 1.0
     */
    get_header();
    global $wpdb, $st, $dPost;
    $cat_id            = get_query_var('cat');
    $author            = get_user_by('slug', get_query_var('author_name'));
    $user_id           = $author->ID;
    $author_info       = get_userdata($user_id);
    $favorite_post_ids = $st->get_knowledges($user_id);
    $post_per_page     = wpfp_get_option("post_per_page");
    $page              = intval(get_query_var('paged'));
    $qry               = array(
        'post__in'       => $favorite_post_ids,
        'posts_per_page' => $post_per_page,
        'orderby'        => 'post__in',
        'paged'          => $page,
    );
    query_posts($qry);
?>

<section id="primary" class="content-area">
    <?php do_action('author-page-header'); ?>
    <main id="main" class="site-main" role="main">
        <header class="page-header" id="author-page">
            <div class="avatar inline ">
                <?= get_avatar($author_info->user_email, 96); ?>
                <h1 style="margin-left: 20px;"
                    class="inline entry-title"><?php print_r($author_info->data->display_name); ?></h1>
            </div>
            <div style="margin-bottom:20px;">
                <div class="about"><?= get_user_meta($author_info->ID, 'description')[0]; ?></div>
            </div>
            <?php if ($favorite_post_ids): ?>
                <div class="wpfp-span">
                    <?php
                        echo "<ul>";
                        while (have_posts()) : the_post();
                            $author_id = get_the_author_meta('ID');
                            if ($author_id === $user_id) {
                                $add_string = '<small class="is_author"> автор </small>';
                            }
                            $passing_date = $dPost->get_passing_info_by_post($author_id, get_the_ID());
                            $passing_string = "<span class='passing_date'>" . $passing_date['date_string'] . "</span>";
                            echo "<li><a href='" . get_permalink() . get_first_unchecked_lesson(get_the_ID()) . "' title='" . get_the_title() . "'>" . get_the_title() . $add_string . "</a> " . $passing_string;
                            diductio_add_progress(get_the_ID(), $user_id);

                            echo "</li>";
                        endwhile;
                        echo "</ul>";
                    ?>
                    <?php
                        //                    moya_zachetka();
                    ?>
                </div>
            <?php else: ?>

            <?php endif; ?>
        </header><!-- .page-header -->
    </main><!-- .site-main -->
</section><!-- .content-area -->

<?php get_footer(); ?>
