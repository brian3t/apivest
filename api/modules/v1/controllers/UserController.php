<?php
/**
 * Created by IntelliJ IDEA.
 * User: tri
 * Date: 8/10/16
 * Time: 10:35 AM
 */

namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\controllers\user\SecurityController;
use app\controllers\user\SettingsController;
use dektrium\user\Finder;
use dektrium\user\models\Account;
use dektrium\user\models\LoginForm;
use dektrium\user\models\User;
use dektrium\user\models\UserSearch;
use dektrium\user\Module;
use dektrium\user\traits\AjaxValidationTrait;
use dektrium\user\traits\EventTrait;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class UserController extends BaseActiveController
{
    public $modelClass='app\models\User';


    protected function verbs()
    {
        $verbs=parent::verbs();
        array_push($verbs['update'],'POST');
    }

    public function actions()
    {
        $actions=parent::actions();

        // disable the default REST actions
        unset($actions['update']);

        return $actions;
    }

    public function actionLogin()
    {
        // Yii::$container
        $response=['status'=>'failed'];
        $module=Yii::$app->getModule('user');
        if($module==null)
        {
            $response['message']='Module user not loaded';
            return $response;
        }
        $settingsController=$module->createController('settings/profile');
        if(!$settingsController || count($settingsController)!=2)
        {
            $response['message']='Settings controller not found';
        }
        $settingsController=$settingsController[0];
        /** @var SettingsController $settingsController */

        /** @var LoginForm $model */
        $model=Yii::createObject(LoginForm::className());
        $search_model=Yii::createObject(UserSearch::className());
        /** @var UserSearch $search_model */

        if($model->load(Yii::$app->getRequest()->post()))
        {
            $finder=$settingsController->getFinder();
            /** @var Finder $finder */
            $user_found=$finder->findUserByEmail($model->login);
            if(is_null($user_found))
            {
                $response['message']='Username does not exist';
                return $response;
            }
            if($model->login())
            {
                $cuser=Yii::$app->user->identity;
                $profile=$settingsController->actionApiProfile();
                $response['status']='ok';
                $response['id']=$user_found->id;
            } else
            {
                $response['message']='Wrong password';
            }
        }
        return $response;

    }

    public function actionUpdate()
    {
        $request=Yii::$app->request;
        $entity_body=Yii::$app->request->post();
        $id=$request->get('id');

        $user=\app\models\User::findOne($id);
        if($user->id!=$id)
        {
            return "Cannot find this user";
        }
        if(isset($entity_body['union_memberships']))
        {
            if(count($entity_body['union_memberships'])==0)
            {
                $entity_body['union_memberships']='';
            } else
            {
                $entity_body['union_memberships']=implode(',',$entity_body['union_memberships']);
            }
        }
        if($user->loadAll(['User'=>$entity_body]) && $user->save(false))
        {
            return json_encode($user->getAttributes());
        } else
        {
            return json_encode($user->errors);
        }

    }

    public function actionPortfolio(){
        $res = [];
        $request=Yii::$app->request;
        $id=$request->get('id');
        if (!$id){
            return "No user id";
        }
        $user=\app\models\User::findOne($id);
        if($user->id!=$id)
        {
            return "Cannot find this user";
        }
        return $user->getPortfolio();
    }
}