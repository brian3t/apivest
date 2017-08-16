<?php

namespace app\controllers\user;

use dektrium\user\controllers\ProfileController as BaseProfileController;
use yii\helpers\BaseFileHelper;
use dektrium\user\Finder;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;


class ProfileController extends BaseFileHelper{

    /**
     * Shows user's profile.
     * @param  integer $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
//	public function actionShow($id)
//	{
//		$profile = $this->finder->findProfileById($id);
//
//		if ($profile === null) {
//			throw new NotFoundHttpException;
//		}
//
//		return $this->render('show', [
//			'profile' => $profile,
//		]);
//	}

    public function actionShow($id)
    {
        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            throw new NotFoundHttpException;
        }

        return $this->render('show', [
            'profile' => $profile,
        ]);
    }
}