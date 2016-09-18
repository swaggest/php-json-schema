<?php
namespace Yaoi\Schema;

/**
 * Interface Validator
 * @package Yaoi\Schema
 * @deprecated in favour of transformer with exceptions for simplicity
 */
interface Validator
{
    /**
     * @param $data
     * @return bool
     */
    public function isValid($data);

}