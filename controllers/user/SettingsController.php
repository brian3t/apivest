<?php
/**
 * Created by IntelliJ IDEA.
 * User: tri
 * Date: 8/11/16
 * Time: 8:48 AM
 */

namespace app\controllers\user;

use dektrium\user\Finder;
use dektrium\user\models\Profile;
use dektrium\user\models\SettingsForm;
use dektrium\user\Module;
use dektrium\user\traits\AjaxValidationTrait;
use dektrium\user\traits\EventTrait;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

class SettingsController extends \dektrium\user\controllers\SettingsController
{
    public function getFinder(){
        return $this->finder;
    }
    public function actionApiProfile(){
        $response = ['status' => 'failed'];
        $model = $this->finder->findProfileById(Yii::$app->user->identity->getId());
    
        if ($model == null) {
            $model = Yii::createObject(Profile::className());
            $model->link('user', Yii::$app->user->identity);
        }
    
        $event = $this->getProfileEvent($model);
    
        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return ['status' => 'success', 'message' => 'Your profile has been updated'];
        }
    
        return $model->attributes;
    }
    
    /**
     *
     * @var \app\models\user\Profile $model
     *
     * Shows profile settings form.
     * @return string|\yii\web\Response
     */
    public function actionProfile() {
        $model = $this->finder->findProfileById( \Yii::$app->user->identity->getId() );
        /** @var $model Profile */
        $this->performAjaxValidation( $model );
        if ( \Yii::$app->request->isPost ) {
            $model->load( \Yii::$app->request->post() );
            $languages = \Yii::$app->request->post('lang');
            if (isset($languages)){
                $languages = array_filter($languages, function($e){return count($e) ==2 ;});
                $model->languages = json_encode($languages);
            }
            $model->avatarFile = UploadedFile::getInstance( $model,'avatarFile' );
            $avatarFolder = \Yii::$app->getBasePath() . "/api/img/avatar/" . \Yii::$app->user->id;
            if ( $model->avatarFile) {
                if (is_dir($avatarFolder)){
                    BaseFileHelper::removeDirectory( $avatarFolder );
                }
                mkdir( $avatarFolder,0775 );
                
                $model->avatarFile->saveAs( $avatarFolder . '/' . $model->avatarFile->baseName . '.' . $model->avatarFile->extension );
                $model->avatar = $model->avatarFile->baseName . '.' . $model->avatarFile->extension;
            }
            if ( $model->save() ) {
                \Yii::$app->getSession()->setFlash( 'success',\Yii::t( 'user','Your profile has been updated' ) );
            }
            return $this->refresh();
        }
        
        return $this->render( 'profile',[ 'model' => $model, ] );
    }
    
}