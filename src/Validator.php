<?php
namespace Yaoi\Schema;

interface Validator
{
    /**
     * @param $data
     * @return bool
     */
    public function isValid($data);

}