<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
 	global $tag, $st;
 	$tag_id = $tag->term_id;
	global $wp_query;
	$args = array( 
	    'tag__in' => $tag_id,
	    'posts_per_page' => -1);
	$tag_posts = get_posts($args);
	foreach ($tag_posts as $tag_key => $tag_value) {
		$category_id = wp_get_post_categories($tag_value->ID);
		$category_info = get_category($category_id[0]);
		$category_link  = get_category_link($category_id[0]);

		$tmp_data['cat_id']   =  $category_info -> term_id;
		$tmp_data['cat_name'] =	 $category_info -> name;
		$tmp_data['cat_link'] =	 $category_link;
		$tag_categories[$category_info -> term_id] = $tmp_data;
	}
	$html2 = "";
	foreach ($tag_categories as $key => $value) {
		$html2 .= '<span class="cat-links 2">';
		$html2 .= '<a href="'.$value['cat_link'].'">'.$value['cat_name'].'</a>';
		$html2 .= '</span>';
	}
?>

<article id="tag-<?=$tag->term_id;?>" class="post-59 post type-post status-publish format-standard hentry category-it-i-tekhnika tag-moskovskijj-gosudarstvennyjj-universitet">

	<header class="entry-header">
		<?php echo "<a href='" . get_tag_link($tag->term_id) . "'><h1 class='entry-title'>".$tag->name."</h1></a>" ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<!-- <div class="add-to-favor-wrapper">
		    <span class="label label-success label-soft">Массивов</span>
		    <span class="label label-success"><?=$tag->count;?></span>
		</div> -->
		<?=$tag->description;?>
	<div class="public_statistic">
		
	</div>
	</div><!-- .entry-content -->
	<footer class="entry-footer">
		<div class="footer-statistic">
			<div class="stat-col">
				<span class="label label-success label-soft">Массивов</span>
		    	<span class="label label-success"><?=$tag->count;?></span>
			</div>
		</div>
		<?=$html2;?>		
	</footer>
</article><!-- #post-## -->
