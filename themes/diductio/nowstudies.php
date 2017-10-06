<?php
/*
 * Template Name: Сейчас проходят
 * Данный шаблон страницы выводит всех пользователей на сайте, которые проходят какие-либо массивы
 * либо выводит свободных пользователей
*/

global $st, $wp_roles;
if (is_page('people-active')) {
    //Busy people
    $user_in = $st->get_all_users('active_users');
} else {
    //Free peoples
    $exclude_of = $st->busy_peoples;
}
$roles = array();
foreach ($wp_roles->roles as $rKey => $rvalue) {
    $roles[] = $rKey;
}
// The Query
$args = array(
    'role__in' => $roles,
);

if ($user_in) {
    $args['include'] = $user_in;
}
if ($exclude_of) {
    $args['exclude'] = $exclude_of;
}
if (!empty($args['include']) || !empty($args['exclude'])):
    
    //Build pagination
    $user_query = new WP_User_Query($args);
    $total_users = count($user_query->get_results());
    $page = max(1,get_query_var('paged'));
    
    // how many users to show per page
    $users_per_page = 80;
    $total_pages = 1;
    $offset = $users_per_page * ($page - 1);
    $total_pages = ceil($total_users / $users_per_page);
    $args['number'] = $users_per_page;
    $args['offset'] = $offset;
    
    //Get users according pagination rule
    $user_query = new WP_User_Query($args);
    get_header(); ?>
    
    <div id="primary" class="content-area">
        <?php do_action('all-peoples-header'); ?>
        <main id="main" class="site-main" role="main">
            <article id="users-page" class="page type-page status-publish hentry">
                <header class="entry-header">
                    <h1 class="entry-title">Люди</h1>
                    <div class="entry-content all-users">
                        <?php
                        // User Loop
                        if (!empty($user_query->results)) {
                            foreach ($user_query->results as $user) {
                                get_template_part('content', 'peoples');
                            }
                        } else {
                            echo 'No users found.';
                        }
                        ?>
                    </div>
                </header>
            </article>
            <?php
            // grab the current query parameters
            $query_string = $_SERVER['QUERY_STRING'];
            $slug = basename(get_permalink());
            $paginate_url =  home_url()."/{$slug}/page/";
            // if on the front end, your base is the current page
            //$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';

            $page_args =  array(
                'base' => str_replace( $big = 999999999, '%#%', $paginate_url.$big ), // the base URL, including query arg
                'format' => '&p=%#%', // this defines the query parameter that will be used, in this case "p"
                'prev_text' => __('&laquo; Previous'), // text for previous page
                'next_text' => __('Next &raquo;'), // text for next page
                'total' => $total_pages, // the total number of pages we have
                'current'  => $page,
                'end_size' => 1,
                'mid_size' => 5,
            );
            ?>
            <nav class="navigation pagination custom-page-wrapper" role="navigation">
                <div class="nav-links custom-pagination">
                    <?php
                    echo paginate_links( $page_args);
                    ?>
                </div>
            </nav>
        </main><!-- .site-main -->
    </div><!-- .content-area -->
<?php else: ?>
<?php endif; ?>
<?php get_footer(); ?>

