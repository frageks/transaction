<?php
/**
 * Author: frageks.
 * Date: 12/09/17.
 * Time: 10:14.
 */

namespace app\controllers;

use app\forms\LoginForm;
use app\forms\SignupForm;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class AuthController extends Controller
{
	/**
	 * Login action.
	 *
	 * @return Response|string
	 */
	public function actionLogin ()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($model->login()) {
				return $this->redirect(Url::to(['/common/users']));
			} else {
				Yii::$app->session->setFlash("error", "Wrong login.");
				return $this->redirect(Url::to(['login']));
			}
		}
		return $this->render('login', [
			'model' => $model,
		]);
	}

	/**
	 * @return string|Response
	 */
	public function actionSignup ()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->redirect('/');
		}

		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($user = $model->signup()) {
				if (Yii::$app->getUser()->login($user)) {
					return $this->redirect(Url::to(['/common/users']));
				}
			}
		}
		if (!empty($model->errors)) {
			Yii::$app->session->setFlash('error', Yii::t('app', "Something went wrong..."));
		}
		return $this->render('signup', [
			'model' => $model,
		]);
	}

	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->redirect(Url::to(['login']));
	}

}