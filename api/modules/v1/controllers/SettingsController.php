<?php
namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\api\base\RequestBody;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use Yii;
use dektrium\user\models\SettingsForm;
use dektrium\user\models\User;

/**
 * Class SettingsController
 * @package app\api\modules\v1\controllers
 * @property array $catalog_items
 */
class SettingsController extends BaseActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\SettingsForm';
    
    public function actionAccount()
    {
        /** @var SettingsForm $model */
        $data = $_POST['settings-form'];
        // $user = new User();
        $user = User::findOne(['username' => $data['username']]);
        if ($user->username != null) {
            $user->password = $data['new_password'];
            $result = $user->save();
            if ($result) {
                return true;
            } else {
                return $user->errors;
            }
        }
        // echo "false";
        return false;
    }
    
    
}