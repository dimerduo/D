 <?php if ( is_user_logged_in() ): ?> 
	<?php if(!empty($user_comments)): ?>
		<?php foreach($user_comments as $comment): ?>
			<?php
			$comment_link = esc_url( get_comment_link( $comment->comment_ID ) );
			$post_link = esc_url( get_permalink( $comment->comment_post_ID ) );
			$output = sprintf( _x( '%1$s', 'widgets' ),
						'<div><a class="link-style-1" href="' . $post_link . '">'. get_the_title( $comment->comment_post_ID ) . '</a></div>'
					);
			?>
			<div class="col-md-12 my-comment">
				<div><?=$output;?></div>
				<div><?=$comment->comment_content?> <a class="link-style-1" href="<?=$comment_link?>">&nbsp; #</a></div>
				<div class="divider"></div>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<h2>На данный момент вы не добавили комментарии</h2>
	<?php endif; ?>
  <?php else: ?>
  	 <h2>Для просмотра данной страницы вы должны быть авторизованы</h2>
 <?php endif; ?>