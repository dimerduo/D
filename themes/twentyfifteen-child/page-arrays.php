<?php
/**
 * Template Name: Шаблон страницы Массивы
 */

get_header(); ?>

	<div id="primary" class="content-area">
			<div id="statistic" class="hentry">
			<div class="public_statistic row">
				<div class="stat-col">
					<a href="<?=get_site_url();?>">
						<span class="label label-success label-soft">Массивы знаний</span>
						<span class="label label-success"><?=$st->get_all_arrays();?></span>
					</a>
				</div>
				<div class="stat-col">
					<a href="/aktivnye-massivy">
						<span class="label label-success 
							<?php if(!is_page('aktivnye-massivy')): ?>label-soft<?php endif; ?> ">Сейчас проходят</span>
						<span class="label label-success"><?=$st->active;?></span>
					</a>
				</div>
				<div class="stat-col">
					<a href="/projjdennye-massivy">
						<span class="label label-success <?php if(!is_page('projjdennye-massivy')): ?>label-soft<?php endif; ?>">Недавно прошли</span>
						<span class="label label-success"><?=$st->done;?></span>
					</a>
				</div>
				<div class="stat-col">
					<a href="/istochniki">
						<span class="label label-success label-soft">Источники</span>
						<span class="label label-success"><?=$st->get_istochiki_count();?></span>
					</a>
				</div>
			</div>
			<!--<div class="public_statistic row precent-row">
				<div class="stat-col">
					<span class="label label-success label-soft">Люди</span>
					<span class="label label-success"><?=$st->get_all_users();?></span>
				</div>
				<div class="stat-col">
					<span class="label label-success label-soft">Сейчас проходят</span>
					<span class="label label-success"><?=$st->active_studies_users;?></span>
				</div>
				<div class="stat-col">
					<span class="label label-success label-soft">Недавно прошли</span>
					<span class="label label-success"><?=$st->finished_study_users;?></span>
				</div>
				 <div class="stat-col">
					<span class="label label-success label-soft">Всего</span>
					<span class="label label-success">523К</span>
				</div> 
				<div class="stat-col">
					<span class="label label-important-soft">Общий уровень</span>
					<span class="label label-important"><?=$st->get_rating();?> %</span>
				</div>
			</div>-->
		</div>
		<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		if(is_page('projjdennye-massivy')) {
			$active_flag = true;
			$courses = get_courses();
			$paginate_url = home_url()."/projjdennye-massivy/";
		} elseif ('aktivnye-massivy') { 
			$courses = get_courses(false);
			$active_flag = false;
			$paginate_url = home_url()."/aktivnye-massivy/";
		}


		$current_page = 1;
		if(get_query_var('page')) 
			$current_page = get_query_var('page');

		$page_count = ceil(count($courses)/10);
		$courses_count = count($courses);
		$courses = array_slice($courses, ($current_page -1 ) * 10, 10, true);
		foreach($courses as $post)
		{
			
			global $post;

			if($post->ID) {
		    	setup_postdata($post);
				get_template_part( 'content', 'array' );
			}
		}

		$args = array(
			'base'         => str_replace( $big = 999999999, '%#%', $paginate_url.$big ),
			// 'format'       => '?page=%#%',
			'total'        => $page_count,
			'current'      => max(1,get_query_var('page')),
			'show_all'     => False,
			'end_size'     => 1,
			'mid_size'     => 2,
			'prev_next'    => True,
			'prev_text'    => __('« Previous'),
			'next_text'    => __('Next »'),
			'type'         => 'list',
			'add_args'     => False,
			'add_fragment' => '',
			'before_page_number' => '',
			'after_page_number' => ''
		); 

      	

		?>
		<?php if($courses_count > 10): ?>
			<nav class="navigation pagination custom-page-wrapper" role="navigation">
				<div class="nav-links custom-pagination">
					<?php echo paginate_links( $args ); ?>
				</div>
			</nav>
		<?php endif; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
