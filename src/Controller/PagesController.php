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
        $password                        = [];
        $length                          = $request->query->getInt('length');
        $uppercaseLetterOptionIsChecked  = $request->query->getBoolean('uppercase-letters');
        $numberOptionIsChecked           = $request->query->getBoolean('numbers');
        $specialCharacterOptionIsChecked = $request->query->getBoolean('special-characters');
        $specialCharacters               = ['!', '#', '$', '%', '&', '(', ')', '*', '+', '-', '|', '_', '^',
            '?', '@', '[', ']', '<', '>'];

        while (count($password) < $length) {
            $password[] = range('a', 'z')[array_rand(range('a', 'z'))];

            if ($uppercaseLetterOptionIsChecked) {
                $password[] = range('A', 'Z')[array_rand(range('A', 'Z'))];
            }

            if ($numberOptionIsChecked) {
                $password[] = range('0', '9')[array_rand(range('0', '9'))];
            }

            if ($specialCharacterOptionIsChecked) {
                $password[] = $specialCharacters[array_rand($specialCharacters)];
            }
        }

        $password = implode('', $password);

        if (strlen($password) > $length) {
            $elementsToDelete = strlen($password) - $length;
            $password         = substr($password, 0, -$elementsToDelete);
        }

        return $this->render('pages/password.html.twig', compact('password'));
    }
}
