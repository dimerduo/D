<?php 
/*
 * Template Name: Сейчас проходят
 * Данный шаблон страницы выводит всех пользователей на сайте, которые проходят какие-либо массивы
*/
global $st, $wp_roles;
if(is_page('people-active')) {
	$user_in = $st->get_all_users('active_users');
} else {
	$exclude_of = $st->busy_peoples;
}

$roles = array();
foreach ($wp_roles->roles as $rKey => $rvalue) {
	$roles[] = $rKey;
}
// The Query
$args = array(
	'role__in' => $roles
);

if ($user_in) {
	$args['include'] = $user_in;
}
if($exclude_of){
	$args['exclude'] = $exclude_of;
}
//print_r($args);exit;

$user_query = new WP_User_Query( $args );


get_header(); ?>

	<div id="primary" class="content-area">
		<?php do_action('people-studying-header'); ?>
		<main id="main" class="site-main" role="main">
			<article id="users-page" class="page type-page status-publish hentry">
				<header class="entry-header">
					<h1 class="entry-title">Люди</h1>
					<div class="entry-content all-users">
						
							<?php 
								// User Loop
								if ( ! empty( $user_query->results ) ) {
									foreach ( $user_query->results as $user ) {
										get_template_part( 'content', 'peoples' );
									}
								} else {
									echo 'No users found.';
								}
							?>
					</div>
	
				</header>
			</article>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>

