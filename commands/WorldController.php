<?php

namespace app\commands;

use yii\console\Controller;
use app\models\User;
use app\models\Profile;
use Faker;

class WorldController extends Controller
{
    public function actionAddPeople($num = 20)
    {
        $faker = Faker\Factory::create();

        for ($i = 1;$i <= $num;$i++)
        {
            $user = new User();

            $user->username = $faker->userName . 'b3t';
            $user->email = $faker->email;
            $user->password = '123456';
            $user->is_ai = 1;
            if($user->save(false))
            {
                $profile = $user->profile;

                $profile->name = $faker->firstName . ' ' . $faker->lastName;
                $profile->city = $faker->city;
                $profile->state = Faker\Provider\en_US\Address::state();
                $profile->state_abbr = Faker\Provider\en_US\Address::stateAbbr();
                $profile->ai_point = 0;
                $profile->save();
            }
        }

    }

}