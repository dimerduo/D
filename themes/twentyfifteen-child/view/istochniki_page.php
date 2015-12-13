<?php 
$html = '<div class="post_tags">';

	foreach ( $tags as $tag ) {
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
		$tag_link = get_tag_link( $tag->term_id );
		$html .= "<div class='tag'><h1 class='entry-title'><a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
		$html .= "{$tag->name}</a></h1>";
		$html .= "<div class='post-descr'>
				{$tag->description}</div><div class='tag_bottom'></div>
				<div class='istochiniki-rubriki'><span class='label label-success'>Массивов  - {$tag->count}</span>&nbsp;&nbsp;&nbsp;{$html2}</div>
		</div>";
	}
	$html .= '</div>';
echo $html;

?>