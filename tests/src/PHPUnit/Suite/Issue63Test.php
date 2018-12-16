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

        $this->assertSame(
            '{"id":123,"first_name":"first","last_name":"last","age":10}',
            json_encode(User63::export($user))
        );

        // no exception expected
        $user->validate();


        $this->assertSame(
            '{"id":123,"first_name":"first","last_name":"last","age":10}',
            json_encode($user->jsonSerialize())
        );

        $this->assertSame(
            '{"id":123,"first_name":"first","last_name":"last","age":10}',
            json_encode($user)
        );
    }
}