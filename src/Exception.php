<?php

namespace Yaoi\Schema;

class Exception extends \Exception
{
    const INVALID_VALUE = 1;
    const NOT_IMPLEMENTED = 2;

    protected $structureTrace = array();
    private $originalMessage;

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        $this->originalMessage = $message;
        parent::__construct($message, $code, $previous);
    }

    public $constraint;
    public function setConstraint(Constraint $constraint)
    {
        $this->constraint = $constraint;
        return $this;
    }
}