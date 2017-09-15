<?php
/**
 * Author: frageks.
 * Date: 12/09/17.
 * Time: 12:15.
 */

namespace app\controllers;


use app\forms\TransactionForm;
use app\models\TransactionHistory;
use app\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CommonController extends Controller
{
	public function actionUsers ()
	{
		return $this->render('users', [
			'users' => User::find()->asArray()->all(),
		]);
	}

	/**
	 * @return string|\yii\web\Response
	 * @throws NotFoundHttpException
	 */
	public function actionSend ()
	{
		if (Yii::$app->user->isGuest) {
			throw new NotFoundHttpException("You can\'t send funds.");
		}
		$model = new TransactionForm();
		$request = Yii::$app->request;
		if ($request->post()) {
			$model->load($request->post());
			$transaction_history = $model->process();
			if ($transaction_history && User::changeBalance($transaction_history)) {
				return $this->redirect(Url::to(['users']));
			}
		}
		return $this->render('send', [
			'model' => $model,
		]);
	}

	/**
	 * @return string
	 */
	public function actionHistory ()
	{
		$data = TransactionHistory::find()->where(['sender' => Yii::$app->user->id])
		                          ->orWhere(['receiver' => Yii::$app->user->id])->all();
		return $this->render("history", [
			'data' => $data,
		]);
	}
}