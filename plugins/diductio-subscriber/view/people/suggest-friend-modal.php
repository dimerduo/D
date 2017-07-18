
<div class="stat-col">
    <div class="add-to-favor-wrapper">
                <span class="wpfp-span">
                    <a id="suggest-to-user" data-toggle="modal" data-target="#suggestUser">Добавить людям</a>
                </span>
    </div>
</div>

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="suggestUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавить людям</h4>
            </div>
            <div class="modal-body all-users">
                <div class="row">
                    <?php if($suggesting_users): ?>
                        <?php foreach ($suggesting_users as $user):
                            $Did_Categories = new Did_Categories();
        
                            $user_id = $user->ID;
        
                            $author_info = get_userdata($user_id);
                            $user_statistic = $st->get_user_info($user_id);
                            $category_statistic = $Did_Categories
                                ->fetchCategoriesByUser($user_id)
                                ->orderBy('value', 'desc')
                                ->max();
                            $user_statistic['author'] = Did_User::getAllMyPosts($user_id);
        
                            $tag_statistic = $Did_Categories
                                ->fetchTagsByUser($user_id)
                                ->orderBy('value', 'desc')
                                ->max();
        
        
                            ?>
                            <div class="col-md-12">
                                <label style="display: block;" for="user-<?=$user->ID;?>">
                                    <div id="user-selecting" class="col-md-1">
                                        <input <?php if($user->is_selected): ?> checked="checked" disabled="disabled" <?php endif;?> id="user-<?=$user->ID;?>" data-user="<?=$user->ID;?>" class="suggested-user" type="checkbox" value="test">
                                    </div>
                                    <?php view(
                                        'people.single-row',
                                        compact(
                                            'user_statistic',
                                            'category_statistic',
                                            'author_info',
                                            'tag_statistic',
                                            'user_id',
                                            'dPost',
                                            'favorite_post_ids',
                                            'will_busy_days',
                                            'enable_link'
                                        )
                                    );
                                    ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="modalMessage">Нет взаимных подписок</div>
                    <?php endif;?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button onclick="suggestToUser.save()" type="button" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>