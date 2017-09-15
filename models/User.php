<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/*
 * User model
 *
 * @property integer $id
 * @property string  $username
 * @property string  $auth_key
 * @property integer  $balance
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
	/**
	 * @inheritdoc
	 */
	public function rules ()
	{
		return array_merge(parent::rules(), [
			['username', 'required'],
			['username', 'string'],
			['balance', 'number'],
		]);
	}

	/**
	 * @inheritdoc
	 *
	 * @return User
	 */
	public static function findIdentity ($id)
	{
		return static::findOne(['id' => $id]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken ($token, $type = null)
	{
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 *
	 * @return static|null
	 */
	public static function findByUsername ($username)
	{
		return static::findOne(['username' => $username]);
	}

	/**
	 * @inheritdoc
	 */
	public function getId ()
	{
		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey ()
	{
		return $this->auth_key;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey ($authKey)
	{
		return $this->auth_key === $authKey;
	}

	/**
	 * @return string
	 */
	public static function tableName ()
	{
		return '{{%user}}';
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey ()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * @return array
	 */
	public static function primaryKey ()
	{
		return ['id'];
	}

	/**
	 * @param TransactionHistory $object
	 *
	 * @return bool
	 */
	public static function changeBalance (TransactionHistory $object)
	{
		/* @var $sender User */
		/* @var $receiver User */
		$sender = self::findOne($object->sender);
		$receiver = self::findOne($object->receiver);
		$sender->balance -= $object->sum;
		$receiver->balance += $object->sum;
		if ($sender->save() && $receiver->save()) {
			return true;
		}
		return false;
	}
}
