<?php

namespace App\Service;

use Exception as ExceptionAlias;

class PasswordGenerator
{
    /**
     * @param int $length
     * @param bool $uppercaseLetterOptionIsChecked
     * @param bool $numberOptionIsChecked
     * @param bool $specialCharacterOptionIsChecked
     * @return string
     * @throws ExceptionAlias
     */
    public function generate(int $length, bool $uppercaseLetterOptionIsChecked = false, bool $numberOptionIsChecked = false, bool $specialCharacterOptionIsChecked = false): string
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
            [$numberOptionIsChecked, range('0', '9')],
            [$specialCharacterOptionIsChecked, $specialCharacters],

        ];

        while (count($password) < $length) {
            foreach ($mapping as [$optionIsChecked, $rangeOfCharactersAccordingToTheOptionChecked]) {
                $password[] = $this->pickRandomItemFromARangeOfCharacters(range('a', 'z'));
                if ($optionIsChecked) {
                    $password[] = $this->pickRandomItemFromARangeOfCharacters($rangeOfCharactersAccordingToTheOptionChecked);
                }
            }
        }


        $password = $this->secureShuffle($password);

        $password = implode('', $password);

        if (strlen($password) > $length) {
            $elementsToDelete = strlen($password) - $length;
            $password         = substr($password, 0, -$elementsToDelete);
        }

        return $password;
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