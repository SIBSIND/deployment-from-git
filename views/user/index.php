<?php

/* @var $this yii\web\View */
/* @var $user \app\models\Users */
/* @var $users \app\models\Users */
/* @var $pages \yii\data\Pagination */

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Users';
?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default panel-table">
            <div class="panel-heading">User lists
                <div class="tools"><span class="icon mdi mdi-account-add"></span></div>
            </div>
            <div class="panel-body">
                <?php if ($users): ?>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th style="width:35%;">User</th>
                            <th style="width:40%;">Latest activity</th>
                            <th style="width:20%;">Permissions</th>
                            <th style="width:5%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="online">
                                <td class="user-avatar cell-detail user-info">
                                    <?= Html::img('/img/avatar.png'); ?>
                                    <span>Igor Zozulinksyi</span>
                                    <span class="cell-detail-description"><?= $user->email ?></span>
                                </td>
                                <td class="cell-detail">
                                    <span>Action</span>
                                    <span class="cell-detail-description">Repository</span>
                                </td>
                                <td class="cell-detail">
                                    <span class="cell-detail-description"><?= $user->getPermissionsList(); ?></span>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-hspace">
                                        <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">Action <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                                        <ul role="menu" class="dropdown-menu pull-right">
                                            <li><a href="#">Latest activity</a></li>
                                            <li><a href="#">Modify</a></li>
                                            <li><a href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?= LinkPager::widget(['pagination' => $pages]); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
