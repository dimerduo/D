<?php
class Did_EmailTemplates
{
    const POST_ADDED_TO_USERS_CABINET = [
        'subject' => 'Вам добавлена запись',
        'body' => '(Формат): {post_link} <br/> Добавил: {user_link}'
    ];
    
    const ANSWERED_TO_MY_COMMENT = [
        'subject' => 'Ответ на комментарий',
        'body' => 'На Ваш комментарий: {my_comment} <br/> Ответили {comment_answer} #'
    ];
}
