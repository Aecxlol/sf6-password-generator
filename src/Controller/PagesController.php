<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request): Response
    {
        $password = '';

        $length                          = $request->query->getInt('length');
        $uppercaseLetterOptionIsChecked  = $request->query->getBoolean('uppercase-letters');
        $numberOptionIsChecked           = $request->query->getBoolean('numbers');
        $specialCharacterOptionIsChecked = $request->query->getBoolean('special-characters');


        $characters                 = range('a', 'z');
        $lowercaseLettersArraySize  = count(range('a', 'z'));
        $characters                 = array_merge($characters, range('A', 'Z'));
        $uppercaseLettersArraySize  = count(range('A', 'Z'));
        $characters                 = array_merge($characters, range(0, 9));
        $numbersArraySize           = count(range(0, 9));
        $specialCharacters          = ['!', '#', '$', '%', '&', '(', ')', '*', '+', '-', '|', '_', '^',
            '?', '@', '[', ']', '<', '>'];
        $characters                 = array_merge($characters, $specialCharacters);
        $specialCharactersArraySize = count($specialCharacters);


        while (strlen($password) < $length) {
            $password = $password . $characters[mt_rand(0, $lowercaseLettersArraySize - 1)];

            if ($uppercaseLetterOptionIsChecked) {
                $password = $password . $characters[mt_rand($lowercaseLettersArraySize, $lowercaseLettersArraySize + $uppercaseLettersArraySize - 1)];
            }

            if ($numberOptionIsChecked) {
                $password = $password . $characters[mt_rand($lowercaseLettersArraySize + $uppercaseLettersArraySize, $lowercaseLettersArraySize + $uppercaseLettersArraySize + $numbersArraySize - 1)];
            }

            if ($specialCharacterOptionIsChecked) {
                $password = $password . $characters[mt_rand($lowercaseLettersArraySize + $uppercaseLettersArraySize + $numbersArraySize, $lowercaseLettersArraySize + $uppercaseLettersArraySize + $numbersArraySize + $specialCharactersArraySize - 1)];
            }
        }

        if (strlen($password) > $length) {
            $elementsToDelete = strlen($password) - $length;
            $password         = substr($password, 0, -$elementsToDelete);
        }

        $password = str_shuffle($password);

        return $this->render('pages/password.html.twig', [
            'password' => $password
        ]);
    }
}
