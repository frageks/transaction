<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%transaction_history}}".
 *
 * @property integer $id
 * @property integer $sender
 * @property integer $receiver
 * @property double  $sum
 * @property integer $when
 *
 * @property User    $receiver0
 * @property User    $sender0
 */
class TransactionHistory extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName ()
	{
		return '{{%transaction_history}}';
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors ()
	{
		return [
			'timestamp' => [
				'class'      => 'yii\behaviors\TimestampBehavior',
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['when'],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules ()
	{
		return [
			[['sender', 'receiver', 'sum'], 'required'],
			[['sender', 'receiver', 'when'], 'integer'],
			[['sum'], 'number', 'numberPattern' => '/^\d+(.\d{1,2})?$/'],
			[['receiver'], 'exist', 'skipOnError'     => true, 'targetClass' => User::className(),
			                        'targetAttribute' => ['receiver' => 'id']],
			[['sender'], 'exist', 'skipOnError'     => true, 'targetClass' => User::className(),
			                      'targetAttribute' => ['sender' => 'id']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels ()
	{
		return [
			'id'       => 'ID',
			'sender'   => 'Sender',
			'receiver' => 'Receiver',
			'sum'      => 'Sum',
			'when'     => 'When',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getReceiver0 ()
	{
		return $this->hasOne(User::className(), ['id' => 'receiver']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getSender0 ()
	{
		return $this->hasOne(User::className(), ['id' => 'sender']);
	}
}
