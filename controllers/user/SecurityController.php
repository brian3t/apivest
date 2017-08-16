<?php

namespace app\controllers\user;


class SecurityController extends \dektrium\user\controllers\SecurityController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['verbs']['actions']['logout']);
        return $behaviors;
    }

    
}