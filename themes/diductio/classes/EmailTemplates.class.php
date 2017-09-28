<?php
class Did_EmailTemplates
{
    const POST_ADDED_TO_USERS_CABINET = [
        'subject' => 'Вам добавлена запись',
        'body' => '{post_format}: {post_link} <br/><br/> Добавил: {user_link} <br/><br/> <i>Сообщение отправлено автоматически, отвечать на него не надо</i>'
    ];
    
    const ANSWERED_TO_MY_COMMENT = [
        'subject' => 'Ответ на комментарий',
        'body' => 'В записи: {post_link} <br/><br/> На Ваш комментарий: {my_comment} <br/><br/> Вам Ответили: <br/> {comment_answer} {comment_link} <br/><br/> <i>Сообщение отправлено автоматически, отвечать на него не надо</i>'
    ];
}
