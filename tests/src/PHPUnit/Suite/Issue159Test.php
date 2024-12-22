<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Suite;

use Swaggest\JsonSchema\Exception\StringException;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class Issue159Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider emailsProvider
     */
    public function testIssue($expected, $email)
    {
        $schemaData = json_decode('{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Root",
  "type": "string",
  "format": "email"
}');

        $schema = Schema::import($schemaData);

        try {
            $schema->in($email);
            $this->assertTrue(true);
        } catch (InvalidValue $e) {
            $this->assertEquals('Invalid email', $e->getMessage());
            $this->assertFalse($expected);
        }
    }

    private function emailsProvider()
    {
        return [
            [true, 'bc@example.com'],
            [true, 'bc.123@example.com'],
            [true, 'ser+mailbox/department=shipping@example.com'],
            [true, '#$%&\'*+-/=?^_`.{|}~@example.com'],
            [true, '"Abc@def"@example.com'],
            [true, '"Fred\ Bloggs"@example.com'],
            [true, '"Joe.\\Blow"@example.com'],
            [false, 'коля@пример.рф'], // valid but FILTER_FLAG_EMAIL_UNICODE supports only unicode in the email address local part. See https://www.php.net/manual/en/filter.constants.php#constant.filter-flag-email-unicode
            [false, '用户@例子.广告'], // valid but FILTER_FLAG_EMAIL_UNICODE supports only unicode in the email address local part. See https://www.php.net/manual/en/filter.constants.php#constant.filter-flag-email-unicode
            [false, 'ಬೆಂಬಲ@ಡೇಟಾಮೇಲ್.ಭಾರತ'], // valid but FILTER_FLAG_EMAIL_UNICODE supports only unicode in the email address local part. See https://www.php.net/manual/en/filter.constants.php#constant.filter-flag-email-unicode
            [false, 'अजय@डाटा.भारत'], // valid but FILTER_FLAG_EMAIL_UNICODE supports only unicode in the email address local part. See https://www.php.net/manual/en/filter.constants.php#constant.filter-flag-email-unicode
            [false, 'квіточка@пошта.укр'], // valid but FILTER_FLAG_EMAIL_UNICODE supports only unicode in the email address local part. See https://www.php.net/manual/en/filter.constants.php#constant.filter-flag-email-unicode
            [false, 'χρήστης@παράδειγμα.ελ'], // valid but FILTER_FLAG_EMAIL_UNICODE supports only unicode in the email address local part. See https://www.php.net/manual/en/filter.constants.php#constant.filter-flag-email-unicode
            [false, 'Dörte@Sörensen.example.com'], // valid but FILTER_FLAG_EMAIL_UNICODE supports only unicode in the email address local part. See https://www.php.net/manual/en/filter.constants.php#constant.filter-flag-email-unicode
            [true, 'коля@example.com'],
            [true, '用户@example.com'],
            [false, 'ಬೆಂಬಲ@example.com'], // valid and should be supported but it's not.
            [true, 'अजय@example.com'],
            [true, 'квіточка@example.com'],
            [true, 'χρήστης@example.com'],
            [true, 'Dörte@Sorensen.example.com'],
            [true, 'simple@example.com'],
            [true, 'very.common@example.com'],
            [true, 'FirstName.LastName@EasierReading.org'],
            [true, 'x@example.com'],
            [true, 'long.email-address-with-hyphens@and.subdomains.example.com'],
            [true, 'user.name+tag+sorting@example.com'],
            [true, 'name/surname@example.com'],
            [false, 'admin@example'], // local domain name with no TLD, although ICANN highly discourages dotless email addresses. See https://en.wikipedia.org/wiki/Email_address#cite_note-29)
            [true, 'example@s.example'],
            [false, '" "@example.org'], // valid. See https://en.wikipedia.org/wiki/Email_address#Internationalization
            [true, '"john..doe"@example.org'],
            [true, 'mailhost!username@example.org'],
            [true, '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"@strange.example.com'],
            [true, 'user%example.com@example.org'],
            [true, 'user-@example.org'],
            [true, 'postmaster@[123.123.123.123]'],
            [true, 'postmaster@[IPv6:2001:0db8:85a3:0000:0000:8a2e:0370:7334]'],
            [true, '_test@[IPv6:2001:0db8:85a3:0000:0000:8a2e:0370:7334]'],
        ];
    }
}