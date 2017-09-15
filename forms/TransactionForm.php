<?php
/**
 * Author: frageks.
 * Date: 13/09/17.
 * Time: 14:48.
 */

namespace app\forms;

use app\models\TransactionHistory;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * TransactionForm is the model behind the transaction form.
 *
 * @property User|null $user This property is read-only.
 */
class TransactionForm extends Model
{
	public $username;
	public $sum;

	/**
	 * @return array
	 */
	public function rules ()
	{
		return [
			[['username', 'sum'], 'required'],
			[['username'], 'string'],
			[['sum'], 'number', 'numberPattern' => '/^\d+(.\d{1,2})?$/'],
		];
	}

	/**
	 * @return TransactionHistory|bool
	 * @throws Exception
	 */
	public function process ()
	{
		if ($this->validate()) {
			$user = User::findOne(['username' => $this->username]);
			if (!$user instanceof User) {
				$signup_model = new SignupForm();
				$signup_model->load(['username' => $this->username], '');
				$user = $signup_model->signup();
			}
			/* @var $user User */
			if ($user->id == Yii::$app->user->id) {
				Yii::$app->session->setFlash('error', "You can't send pounds to yourself.");
				return false;
			}
			$model = new TransactionHistory();
			$model->load(['sum' => $this->sum], '');
			$model->sender = Yii::$app->user->id;
			$model->receiver = $user->id;
			if ($model->save()) {
				return $model;
			}
		}
		return false;
	}
}