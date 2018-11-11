<?php

namespace Swaggest\JsonSchema\Tests\Helper;


class SimpleClass
{
    public $id;
    public $username;
    public $email;


    // checking leak of private/protected properties
    protected $temp1;
    private  $temp2;

    public function __construct()
    {
        $this->temp1 = 'temp1';
        $this->temp2 = 'temp2';
    }
}