<?php
    /*
     * Template Name: Подписки
     * Данный шаблон страницы выводит подписки пользователя
    */
    global $wp_roles, $wp_query;

    if ($wp_query->query_vars['username']) {
        $username       = $wp_query->query_vars['username'];
        $user_data      = get_user_by('slug', $username);
        $logged_user_id = $user_data->ID;
    } else {
        $logged_user_id = get_current_user_id();
    }

    $subscriber_list = get_user_meta($logged_user_id, 'subscribe_to');
    $roles           = array();
    foreach ($wp_roles->roles as $rKey => $rvalue) {
        $roles[] = $rKey;
    }

    if ( ! empty($subscriber_list)) {
        $subscriber_list = $subscriber_list[0];


        $count_args       = array(
            'role__in' => $roles,
            'fields'   => 'all_with_meta',
            'include'  => $subscriber_list,
            'number'   => 999999,
        );
        $user_count_query = new WP_User_Query($count_args);
        $user_count       = $user_count_query->get_results();
        // print_r($user_count);
        // count the number of users found in the query
        $total_users = $user_count ? count($user_count) : 1;
    } else {
        $total_users = 0;
    }
    // grab the current page number and set to 1 if no page number is set
    $page = max(1, get_query_var('paged'));
    // how many users to show per page
    $users_per_page = 80;

    $total_pages = 1;
    $offset      = $users_per_page * ($page - 1);
    $total_pages = ceil($total_users / $users_per_page);

    $args = array(
        // search only for Authors role
        'role__in' => $roles,
        'number'   => $users_per_page,
        'include'  => $subscriber_list,
        'offset'   => $offset // skip the number of users that we have per page
    );
    // Create the WP_User_Query object

    if ($subscriber_list) {
        $user_query = new WP_User_Query($args);
    }

    //collect categories and meta information
    // $category_list = get_user_meta($logged_user_id, 'signed_categories')[0];
    $category_list = $tag_list = null;
    if ( ! empty(get_user_meta($logged_user_id, 'signed_categories'))) {
        $category_list = get_user_meta($logged_user_id, 'signed_categories')[0];
    }
    if ( ! empty(get_user_meta($logged_user_id, 'signed_tags'))) {
        $tag_list = get_user_meta($logged_user_id, 'signed_tags')[0];
    }
    $collector = array();
    if ($category_list) {
        foreach ($category_list as $value) {
            $cat_info        = get_category($value);
            $tmp['name']     = $cat_info->name;
            $tmp['taxonomy'] = $cat_info->taxonomy;
            $tmp['url']      = get_category_link($value);
            $collector[]     = $tmp;
        }
    }
    if ($tag_list) {
        foreach ($tag_list as $value) {
            $tag_info        = get_tag($value);
            $tmp['name']     = $tag_info->name;
            $tmp['taxonomy'] = $tag_info->taxonomy;
            $tmp['url']      = get_tag_link($value);
            $collector[]     = $tmp;
        }
    }
    $data->taxonomies = $collector;
    get_header(); ?>

<div id="primary" class="content-area">
    <?php do_action('progress-subscribers-header'); ?>
    <main id="main" class="site-main" role="main">
        <article id="users-page" class="page type-page status-publish hentry">
            <header class="entry-header">
                <h1 class="entry-title">Мои подписки</h1>
                <div class="entry-content all-users">

                    <?php
                        // User Loop
                        if ( ! empty($user_query->results)) {
                            foreach ($user_query->results as $user) {
                                get_template_part('content', 'peoples');
                            }
                        } else {
                            echo 'No users found.';
                        }
                    ?>
                </div>
                <?php
                    if (function_exists('loadView')) {
                        loadView('my-taxonomies', $data);
                    }
                ?>
            </header>
        </article>
        <?php
            // grab the current query parameters
            $query_string = $_SERVER['QUERY_STRING'];

            // The $base variable stores the complete URL to our page, including the current page arg

            // if in the admin, your base should be the admin URL + your page
            // $base = 'http://5.178.82.26/lyudi/' . remove_query_arg('p', $query_string) . '%_%';
            $paginate_url = home_url() . "/people/page/";
            // if on the front end, your base is the current page
            //$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';

            $page_args = array(
                'base'   => str_replace($big = 999999999, '%#%', $paginate_url . $big),
                // the base URL, including query arg
                'format' => '&p=%#%',
                // this defines the query parameter that will be used, in this case "p"

                'prev_text' => __('&laquo; Previous'),
                // text for previous page
                'next_text' => __('Next &raquo;'),
                // text for next page
                'total'     => $total_pages,
                // the total number of pages we have
                'current'   => $page,
                'end_size'  => 1,
                'mid_size'  => 5,
            );
        ?>
        <nav class="navigation pagination custom-page-wrapper" role="navigation">
            <div class="nav-links custom-pagination">
                <?php
                    echo paginate_links($page_args);
                ?>
            </div>
        </nav>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
