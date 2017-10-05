<?php

class Did_comments
{
    
    /**
     * Did_comments constructor.
     */
    public function __construct()
    {
        add_action('wp_insert_comment', array($this, 'comment_added'), 99, 2);
        add_action('wp_set_comment_status', array($this, 'comment_added'), 99, 2);
    }
    
    /**
     * @param            $id
     * @param WP_Comment $comment
     * @return bool
     */
    public function comment_added($id, $comment)
    {
        if ($comment->comment_approved == 1 && $comment->comment_parent > 0) {
            $parent = get_comment($comment->comment_parent);
            $email = $parent->comment_author_email;
            $post_id = $comment->comment_post_ID;
            $my_comment = $parent->comment_content;
            $comment_answer = $comment->comment_content;
            
            
            if ($email === $comment->comment_author_email) {
                return false;
            }
            
            $subject = Did_EmailTemplates::ANSWERED_TO_MY_COMMENT['subject'];
            $message = Did_EmailTemplates::ANSWERED_TO_MY_COMMENT['body'];
            
            $post_url = get_permalink($post_id);
            $post_name = get_the_title($post_id);
            $comment_link = get_comment_link($comment->comment_ID);
            $who_commented = get_user_by('email', $comment->comment_author_email);
            $user_link = get_site_url() . "/people/" . $who_commented->data->user_nicename;
            
            $find = array('{post_link}', '{my_comment}', '{comment_answer}', '{comment_link}', '{user_link}');
            $replace = array(
                sprintf("<a href='%s'>%s</a>", $post_url, $post_name),
                $my_comment,
                $comment_answer,
                sprintf("<a href='%s'>#</a>", $comment_link),
                sprintf("<a href='%s'>%s</a>", $user_link, $who_commented->display_name),
            );
            $message = str_replace($find, $replace, $message);
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $res = wp_mail($email, $subject, $message, $headers);
        }
        
    }
}