<?php
/**
 * My progress view
 * Вьюшка отвечающая за отображение Блока "мой прогресс" с активными задачами в сайдбаре
 */
?>

<?php
    $i = 0;
?>

<ul>
   
    <!-- My progress view  -->
    <?php if(is_user_logged_in()): ?>
    <li>
        <a href="/progress">
            Мой прогресс
            <div style='float: right; margin-right: 0;' class='stat-col'>
                <span class='label label-success label-soft label-short' data-toggle="tooltip" data-placement="top" title="Активных"><?=$user_statistic['in_progress'];?></span>
                <?php if($user_statistic['overdue_tasks'] > 0): ?>
                    <span class="label label-danger label-short" data-toggle="tooltip" data-placement="top" title="Просроченных"><?=$user_statistic['overdue_tasks'];?></span>
                <?php endif; ?>
            </div>
        </a>
    </li> 
    
    <?php foreach ($knowledges as $knowledge):            
        $pass_info = $GLOBALS['dPost']->get_passing_info_by_post($user_ID, $knowledge->ID);
        $added_by = Did_Statistic::addedBy($knowledge->ID, $user_ID);
        
        /*
            Информация о результатах выполнения задач.
        */        
        // Выводим фактический прогресс
        $actual_progress = $GLOBALS['st']->get_user_progress_by_post($knowledge->ID, $user_ID);        
        // Считаем расчетный прогресс. (Примечание, обязательно в админ-панели должен быть прописан параметр work_time)
        $work_time = get_post_meta($knowledge->ID, 'work_time', true); // Заданное время для выполнения задания.
        $post_statistic = $st->get_course_info($knowledge->ID); // Информация о посте.
        $current_user_id = get_current_user_id(); // ID пользователя
        $started = $post_statistic['users_started'][$current_user_id]; // Начало выполнения задания.
        $now = date_create(); // Сегодняшняя дата.
        $start = date_create($started); // Создаем дату начала выполнения задания.
        $diff = date_diff($now, $start); // Количество пройденных дней с начала выполнения задания.        
        $diff_h_in_days = $diff->h > 0 ? $diff->h / 24 : 0; 
        $estimated_progress = round( ( ($diff->days + $diff_h_in_days) / $work_time ) * 100, 2);
        if ($estimated_progress >= 100) {
            $estimated_progress = 100; 
        }
    ?>
    
    <li class="widget-my-project-list">
        
        <div>
            
            <div class="stat-col margin-right-none">
                <span class="<?php if($estimated_progress > $actual_progress){echo("label label-danger label-short");}else{echo("label label-success label-soft label-short");} ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Фактический прогресс">
                    <?= $actual_progress; ?>
                </span>    
            </div>  
            
            <div class="stat-col">
                <span class="label label-success label-soft label-short" data-toggle="tooltip" data-placement="bottom" data-original-title="Рассчётный прогресс">
                    <?= $estimated_progress; ?>  
                </span>    
            </div>            
               
            <a class="link-style-1" href="<?php echo (get_permalink($knowledge->ID)); ?>" title="<?=$knowledge->post_title;?>">
                <?php 
                    if($knowledges[$i]->post_status == "private"){
                        echo ("Личное: $knowledge->post_title");
                    }else{
                        echo ("$knowledge->post_title");
                    }
                    $i++;
                ?>    
            </a>
            
        </div>
        
        <?php if ($added_by && $added_by->ID != $user_ID): ?>
            <div class="progress-on">
                Добавил:
                <a href="<?= get_site_url(); ?>/people/<?= $added_by->user_nicename ?>">
                    <?=$added_by->display_name?>
                </a>
            </div>
        <?php endif; ?>
        
    </li>
    
    <?php endforeach; ?>
    
    <!-- Navigation  -->
    <li class='row'>
        <div class='col-xs-3 col-md-3 col-sm-3'><a style="font-size: 15px" class="link-style-1" href='/progress'>Всё</a></div>
        <div class='col-xs-5 col-md-5 col-sm-5'><a style="font-size: 15px" class="link-style-1" href='/wp-admin/profile.php'>Настройки</a></div>
        <div style='text-align: right;' class='col-xs-4 col-md-4 col-sm-4 logout'><?=wp_loginout(false, 0);?></div>
    </li>
        
    <!-- Navigation end -->
    <?php else: ?>
        <li><a class="link-style-3" href="<?=wp_registration_url();?>">Регистрация</a></li>
        <li class="logout"><?=wp_loginout(false, 0);?></li>
    <?php endif; ?>
        
</ul>