<?php
/**
 * My progress view
 * Вьюшка отвечающая за отображение Блока "мой прогресс" с активными задачами в сайдбаре
 */
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
        $link = get_permalink($knowledge->ID);
        ?>
    <li class="widget-my-project-list">
        <div>
            <a class="link-style-1" href="<?=$link;?>"><?=$knowledge->post_title;?></a>
        </div>
        <?php if ($added_by && $added_by->ID != $user_ID): ?>
            <div class="progress-on">
                Вам добавил:
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
