<?php

namespace app\forms;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
	const SCENARIO_MUTUAL = 'mutual';

	public $username;
	public $balance;

	/**
	 * @inheritdoc
	 */
	public function rules ()
	{
		return [
			[['username'], 'filter', 'filter' => 'trim'],
			[['username'], 'required'],
			[
				'username',
				'unique',
				'targetClass' => User::className(),
				'message'     => 'This username has already been taken.',
			],
			[['username'], 'string', 'min' => 2, 'max' => 255],
			[['balance'], 'default', 'value' => 0],
			[['balance'], 'integer'],
		];
	}

	public function attributeLabels ()
	{
		return [
			'username' => yii::t('app', 'Username'),
		];
	}

	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 */
	public function signup ()
	{
		if (!$this->validate()) {
			return null;
		}
		if (User::findOne(['username' => $this->username]) instanceof User) {
			Yii::$app->session->setFlash("error", "This user is already exists. Choose another username.");
			return null;
		}
		$user = new User();
		$user->load(['username' => $this->username], '');
		$user->generateAuthKey();

		return $user->save() ? $user : null;
	}
}
