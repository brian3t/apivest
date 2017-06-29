<?php

namespace app\api\base;


class Error
{
    public $event_type = 'error';
    public $code = '';
    public $message, $diagnostic_data = '';
    public $associations = [];
    
    public function __construct(array $values = [])
    {
        
    }
    
    public function print()
    {
    }
}