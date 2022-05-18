<?php

namespace App\Service;

use Exception as ExceptionAlias;

class PasswordGenerator
{
    /**
     * @param int $length
     * @param bool $uppercaseLetterOptionIsChecked
     * @param bool $digitOptionIsChecked
     * @param bool $specialCharacterOptionIsChecked
     * @return string
     * @throws ExceptionAlias
     */
    public function generate(int $length, bool $uppercaseLetterOptionIsChecked = false, bool $digitOptionIsChecked = false, bool $specialCharacterOptionIsChecked = false): string
    {
        $password = [];

        # The ranges are from ascii table
        $specialCharacters = array_merge(
            range('!', '/'),
            range(':', '@'),
            range('[', '`'),
            range('{', '~')
        );

        $mapping = [
            [$uppercaseLetterOptionIsChecked, range('A', 'Z')],
            [$digitOptionIsChecked, range('0', '9')],
            [$specialCharacterOptionIsChecked, $specialCharacters],

        ];

        # We make sure that the password contains at least
        # one character of each option checked
        while (count($password) < $length) {
            foreach ($mapping as [$optionIsChecked, $rangeOfCharactersAccordingToTheOptionChecked]) {
                $password[] = $this->pickRandomItemFromARangeOfCharacters(range('a', 'z'));
                if ($optionIsChecked) {
                    $password[] = $this->pickRandomItemFromARangeOfCharacters($rangeOfCharactersAccordingToTheOptionChecked);
                }
            }
        }

        # We make sure that the password doesn't exceed the length chosen
        # by the user
        $password = array_splice($password, 0, $length);

        # We shuffle the password to make the password to not always have
        # the same order as lowercase-uppercase-digit-specialChar
        $password = $this->secureShuffle($password);

        # Converts the array into a string
        return implode('', $password);
    }

    /**
     * Shuffle all the elements from a given array
     * @param array $arr
     * @return array
     * @throws ExceptionAlias
     */
    private function secureShuffle(array $arr): array
    {
        $length = count($arr);
        for ($i = $length - 1; $i > 0; $i--) {
            $j       = random_int(0, $i);
            $temp    = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }
        return $arr;
    }

    /**
     * Pick a random character from an array
     * @param array $rangeOfCharacters
     * @return string
     * @throws ExceptionAlias
     */
    private function pickRandomItemFromARangeOfCharacters(array $rangeOfCharacters): string
    {
        return $rangeOfCharacters[random_int(0, count($rangeOfCharacters) - 1)];
    }
}