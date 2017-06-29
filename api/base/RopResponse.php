<?php

namespace app\api\base;


use yii\base\Object;
use yii\db\ActiveRecord;

/**
 * Class RopResponse
 * @package app\api\base
 *
 * @property array $errors
 * @property string $status
 * @property integer $count
 */
class RopResponse extends Object
{
    public $errors;
    protected $status = 'success';
    public $count = 0;
    
    /**
     * @param ActiveRecord $model
     * @var ActiveRecord $model
     */
    public function pull_error($model)
    {
        if (!$model->hasErrors()) {
            return;
        }
        $error = new Error();
        $this->status = 'fail';
        $error->message = 'Database error. Model: ' . get_class($model);
        $error->diagnostic_data = $model->getErrors() . " Data: " . $model->getAttributes();
        $this->errors[] = $error;
    }
    
    public function add_error($message = 'Error')
    {
        $error = new Error();
        $error->message = $message;
        $this->errors[] = $error;
        return $this;
    }
    
    public function print()
    {
        $result = ['status' => $this->status];
        if (count($this->errors) > 0) {
            $result['events'] = $this->errors;
        }
        if ($this->count > 0) {
            $result['count'] = $this->count;
        }
        return $result;
    }
}