<?php
/*
 * Template Name: Мои посты (публикации автора)
 * Данный шаблон выводит все авторские посты пользователя
*/
global $wp_query;

$page_template = 'my_posts';
$current_page = get_query_var('paged');
$author = $user_info = get_query_var('username') ? get_user_by('slug', get_query_var('username')) : wp_get_current_user();

if($user_info->ID) {
    query_posts("author={$user_info->ID}&paged={$current_page}");
}


get_header(); ?>
    <div id="primary" class="content-area">
        <?php do_action('author-page-header'); ?>
        <main id="main" class="site-main homepage-main" role="main">
            <?php if ( have_posts() && $user_info->ID ) : ?>
        
                <?php if ( is_home() && ! is_front_page() ) : ?>
                    <header>
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif; ?>
        
                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();
            
                    /*
                     * Include the Post-Format-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */
                    get_template_part( 'content', get_post_format() );
            
                    // End the loop.
                endwhile;
        
                // Previous/next page navigation.
                the_posts_pagination( array(
                    'prev_text'          => __( '->', 'diductio' ),
                    'next_text'          => __( '<-', 'diductio' ),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'diductio' ) . ' </span>',
                ) );
    
            // If no content, include the "No posts found" template.
            else :
                get_template_part( 'content', 'none' );
    
            endif;
            ?>
        </main>
    </div>
<?php get_footer(); ?>

