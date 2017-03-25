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

//Get categories information by user
$category_statistic = $tag_statistic = array();
$Did_Categories = new Did_Categories();
$category_statistic = $Did_Categories->fetchCategoriesByUser($user_id)->orderBy('value','desc')->max();
$tag_statistic = $Did_Categories->fetchTagsByUser($user_id)->orderBy('value','desc')->max();
?>

<section id="primary" class="content-area">
    <?php do_action('author-page-header'); ?>
    <main id="main" class="site-main" role="main">
        <header class="page-header" id="author-page">
            <div class="personal-area">
                <div class="avatar inline ">
                    <div class="inline"><?= get_avatar($author_info->user_email, 96); ?></div>
                    <div style="user-info" class="inline">
                        <h1 class="entry-title"><?=$author_info->data->display_name;?></h1>
                        <div class="about"><?= get_user_meta($author_info->ID, 'description')[0]; ?></div>
                        <div class="user-categories" >
                            <?php view(
                                'user-category-static',
                                compact('category_statistic', 'author_info', 'tag_statistic'));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($favorite_post_ids): ?>
                <div class="wpfp-span public-page-statistic-box">
                    <?php
                        echo "<ul>";
                        while (have_posts()) : the_post();
                            $author_id = get_the_author_meta('ID');
                            if ($author_id === $user_id) {
                                $add_string = '<small class="is_author"> автор </small>';
                            }
                            $passing_date = $dPost->get_passing_info_by_post($user_id, get_the_ID());
                            $passing_string = "<span class='passing_date'>" . $passing_date['date_string'] . "</span>";
                            $on_knowledge = $passing_date['undone_title']
                                ?  '<span class="on-knowldedge"> На этапе: ' . $passing_date['undone_title'] . '</span>'
                                : '';

                            $li  = "<li>";
                                $li .= "<a href='". get_permalink() . get_first_unchecked_lesson(get_the_ID()) ."'";
                                $li .= "title='" . get_the_title() . "'>";
                                    $li .= get_the_title();
                                    //Is author string
                                    $li .= $add_string;
                                $li .= "</a>";
                                //Showing start-end date
                                $li .= $passing_string;
                                //Progress bar
                                $li .= diductio_add_progress(get_the_ID(), $user_id, false);
                                //Show on what knowledge user is now
                                $li .= $on_knowledge;
                            $li .=  "</li>";
                            echo $li;
                        endwhile;
                        echo "</ul>";
                    ?>
                </div>
            <?php else: ?>

            <?php endif; ?>
        </header><!-- .page-header -->
    </main><!-- .site-main -->
</section><!-- .content-area -->

<?php get_footer(); ?>
