<?php
	require_once("../../../wp-load.php");
	require_once("../../../wp-includes/wp-db.php");
	// $wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
	global $current_user;
	global $wpdb;

  	$current_user = wp_get_current_user();
  
	/* (10) Внесение прогресса в таблицу */
	if(isset($_POST['checked_elements'])) {
		$user_id = $current_user->ID;
		if($user_id) {
			$post_id = $_POST['post_id'];
			$user_favorites = get_user_meta($user_id,'wpfp_favorites');

			if(!in_array($post_id, $user_favorites[0] )) {
				array_push($user_favorites[0], $post_id);
				update_user_meta($user_id, 'wpfp_favorites',$user_favorites[0]);
			} 

			$lessons_count = $_POST['lessons_count'];
			$checked_lessons = implode(',', $_POST['checked_elements']);
			$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
			$sql  = "SELECT `id` FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
			$sql .= "AND `post_id` = '{$post_id}'";
			$progress = $thepost = $wpdb->get_row($sql);
			$insert_data = array(
				'user_id' => $user_id, 
				'post_id' => $post_id, 
				'lessons_count' => $lessons_count, 
				'checked_lessons' => $checked_lessons 
				);
			
			if($progress) {
				$wpdb->update($table_name,$insert_data, array('id' =>$progress->id), array("%d","%d","%d","%s"), array("%d","%d","%d","%s") );
			} else {
				$wpdb->insert($table_name, $insert_data, array("%d","%d","%d","%s"));
			}
		}
	}
  	/* (10) Внесение прогресса в таблицу end */
?>
