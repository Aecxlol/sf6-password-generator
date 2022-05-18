<?php

namespace App\Tests\Service;

use App\Service\PasswordGenerator;
use Exception;
use PHPUnit\Framework\TestCase;

class PasswordGeneratorTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_generate_should_respect_password_constraints(): void
    {
        $passwordGenerator = new PasswordGenerator();

        $password = $passwordGenerator->generate(length: 10);
        $this->assertSame(10, mb_strlen($password));
        $this->assertMatchesRegularExpression('/^[a-z]{10}$/', $password);
        $this->assertDoesNotMatchRegularExpression('/[A-Z]/', $password);
        $this->assertDoesNotMatchRegularExpression('/[0-9]/', $password);
        $this->assertDoesNotMatchRegularExpression('/[\W_]/', $password);

        $password = $passwordGenerator->generate(length: 10, uppercaseLetterOptionIsChecked: true);
        $this->assertSame(10, mb_strlen($password));
        $this->assertMatchesRegularExpression('/[a-z]/', $password);
        $this->assertMatchesRegularExpression('/[A-Z]/', $password);
        $this->assertDoesNotMatchRegularExpression('/[0-9]/', $password);
        $this->assertDoesNotMatchRegularExpression('/[\W_]/', $password);

        $password = $passwordGenerator->generate(length: 10, uppercaseLetterOptionIsChecked: true, digitOptionIsChecked: true);
        $this->assertSame(10, mb_strlen($password));
        $this->assertMatchesRegularExpression('/[a-z]/', $password);
        $this->assertMatchesRegularExpression('/[A-Z]/', $password);
        $this->assertMatchesRegularExpression('/[0-9]/', $password);
        $this->assertDoesNotMatchRegularExpression('/[\W_]/', $password);

        $password = $passwordGenerator->generate(length: 10, uppercaseLetterOptionIsChecked: true, digitOptionIsChecked: true, specialCharacterOptionIsChecked: true);
        $this->assertSame(10, mb_strlen($password));
        $this->assertMatchesRegularExpression('/[a-z]/', $password);
        $this->assertMatchesRegularExpression('/[A-Z]/', $password);
        $this->assertMatchesRegularExpression('/[0-9]/', $password);
        $this->assertMatchesRegularExpression('/[\W_]/', $password);
    }
}
