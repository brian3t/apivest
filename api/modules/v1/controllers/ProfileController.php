<?php
namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\models\Profile;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use Yii;


class ProfileController extends BaseActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Profile';
    
    public function actionImage($user_id = null)
    {
        if ($user_id == null) {
            return ['error'=>"Missing user id"];
        }
        if (is_array($_FILES)) {
            $avatar_folder = "/var/www/ermapi/api/img/avatar/$user_id";
            if (!file_exists($avatar_folder)) {
                mkdir($avatar_folder);
            }
            $avatar_file_name = $_FILES['profile']['name']['image'];
            chdir($avatar_folder);
            array_map("unlink", glob("*"));
            if (copy($_FILES['profile']['tmp_name']['image'],
                "$avatar_file_name")) {
                if (Yii::$app->db->createCommand("UPDATE profile set avatar = '$avatar_file_name' WHERE user_id = '$user_id'; ")->query()) {
                    return ['Profile successfully updated'];
                } else {
                    return ['error'=>'Failed to update profile table'];
                }
            } else {
                return ['error'=>'Can not copy file. File too big?'];
            }
        } else {
            return ['error'=>'Missing file'];
        }
    }
}