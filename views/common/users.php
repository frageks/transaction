<?php
/**
 * Author: frageks.
 * Date: 12/09/17.
 * Time: 12:17.
 */

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Users list";
/** @var $users User[] */
?>
<div class="common-users">
    <?php if (!Yii::$app->user->isGuest) { ?>
        <div class="form-group">
            <?= Html::a('Send money', Url::to(['send']), ['class' => 'btn btn-success']) ?>
            <?= Html::a('View history', Url::to(['history']), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>
    <ul>
        <?php foreach ($users as $key => $user) {
            echo "<li>", $user['username'], " (", $user['balance'], ")</li><br>";
        }
        ?>
    </ul>
</div>