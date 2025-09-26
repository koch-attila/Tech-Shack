<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    public function test_hungarian_postal_code_validation()
    {
        $valid = ['1234', '2000', '9999'];
        $invalid = ['123', 'abcd', '12345', '', null];

        foreach ($valid as $code) {
            $this->assertMatchesRegularExpression('/^\d{4}$/', $code);
        }
        foreach ($invalid as $code) {
            $this->assertDoesNotMatchRegularExpression('/^\d{4}$/', $code ?? '');
        }
    }

    public function test_hungarian_phone_number_regex_validation()
    {
        $valid = ['+36201234567', '+36701234567', '06201234567', '06701234567'];
        $invalid = ['+361234567', '1234567', '0036201234567', 'abc', '', null];

        foreach ($valid as $phone) {
            $this->assertMatchesRegularExpression('/^(?:\+36|06)\d{8,9}$/', $phone);
        }
        foreach ($invalid as $phone) {
            $this->assertDoesNotMatchRegularExpression('/^(?:\+36|06)\d{8,9}$/', $phone ?? '');
        }
    }
}
