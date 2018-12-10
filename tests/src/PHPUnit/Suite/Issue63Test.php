<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;

use Swaggest\JsonSchema\Tests\Helper\User63;

class Issue63Test extends \PHPUnit_Framework_TestCase
{
    function testIssue()
    {
        $user = new User63();
        $user->setId(123);
        $user->setFirstName('first');
        $user->setLastName('last');
        $user->setAge(10);
        // no exception expected
        $user->validate();
    }
}