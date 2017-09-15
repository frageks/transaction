<?php

namespace app\forms;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
	public $username;
	private $_user = false;

	/**
	 * @return array the validation rules.
	 */
	public function rules ()
	{
		return [
			[['username'], 'required'],
			[['username'], 'string'],
		];
	}

	/**
	 * Logs in a user using the provided username.
	 *
	 * @return bool whether the user is logged in successfully
	 */
	public function login ()
	{
		if ($this->validate()) {
			$user = $this->getUser();
			if (!$user instanceof IdentityInterface) {
				Yii::$app->session->setFlash("error", "User is not found.");
				return false;
			}
			return Yii::$app->user->login($this->getUser(), 3600 * 24 * 30);
		}
		return false;
	}

	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser ()
	{
		if ($this->_user === false) {
			$this->_user = User::findByUsername($this->username);
		}

		return $this->_user;
	}
}
