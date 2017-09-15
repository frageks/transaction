<?php
/**
 * Author: frageks.
 * Date: 12/09/17.
 * Time: 12:43.
 */

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

/* @var $model \app\forms\TransactionForm */
/* @var $user \app\models\User */
$this->title = "Send funds";
?>
<div class="common-send">
    <h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['placeholder' => 'Enter receiver\'s username']) ?>

	<?= $form->field($model, 'sum')->textInput(['placeholder' => '0.00']) ?>

    <div class="form-group">
		<?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
    </div>

	<?php ActiveForm::end(); ?>
</div>