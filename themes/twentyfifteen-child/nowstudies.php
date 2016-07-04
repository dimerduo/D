<?php 
/*
 * Template Name: Сейчас проходя
 * Данный шаблон страницы выводит всех пользователей на сайте, которые проходят какие-либо массивы
*/
global $st;

if(is_page('people-active')) {
	$user_in = $st->get_all_users('active_users');
} else {
	$user_in = $st->get_all_users('finished_users');
}

// The Query
$args = array(
	'role' => 'Subscriber'
);

if ($user_in) {
	$args['include'] = $user_in;
}

$user_query = new WP_User_Query( $args );


get_header(); ?>

	<div id="primary" class="content-area">
		<div id="statistic" class="hentry">
			<div class="public_statistic row precent-row">
				<div class="stat-col">
					<a href="<?php get_home_url(); ?>/people">
						<span class="label label-important-soft">Люди</span>
						<span class="label label-important"><?=$st->get_all_users();?></span>
					</a>
				</div>
				<div class="stat-col">
					<a href="<?php get_home_url(); ?>/people-active">
						<span class="label label-important <?php if(!is_page('people-active')): ?>label-important-soft<?php endif; ?>">Проходят</span>
						<span class="label label-important"><?=$st->active_studies_users;?></span>
					</a>
				</div>
				<div class="stat-col">
					<a href="<?php get_home_url(); ?>/people-recently">
						<span class="label label-important <?php if(!is_page('people-recently')): ?>label-important-soft<?php endif; ?>">Прошли</span>
						<span class="label label-important"><?=$st->finished_study_users;?></span>
					</a>	
				</div>
				<div class="stat-col">
					<span class="label label-important-soft">Уровень</span>
					<span class="label label-important"><?=$st->get_rating();?> %</span>
				</div>
				<div class="stat-col">
					<a href="<?=get_home_url();?>">
						<span class="label label-success label-soft">Массивы</span>
						<span class="label label-success"><?=$st->finished_study_users;?></span>
					</a>	
				</div>
			</div>
		</div>
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

