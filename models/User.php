<?php

namespace app\models;


class User extends \app\models\base\User
{
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // add field to scenarios
        $scenarios['create'][]   = 'field';
        $scenarios['update'][]   = 'field';
        $scenarios['register'][] = 'field';
        return $scenarios;
    }

    public function rules()
    {
        $rules = parent::rules();
        // add some rules
        $rules['fieldRequired'] = ['field', 'required'];
        $rules['fieldLength']   = ['field', 'string', 'max' => 10];

        return $rules;
    }
}
