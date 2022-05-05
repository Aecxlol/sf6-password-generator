<?php

namespace App\Controller;

use Exception as ExceptionAlias;
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

    /**
     * @throws ExceptionAlias
     */
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
            $password[] = range('a', 'z')[random_int(0, count(range('a', 'z')) - 1)];

            if ($uppercaseLetterOptionIsChecked) {
                $password[] = range('A', 'Z')[random_int(0, count(range('A', 'Z')) - 1)];
            }

            if ($numberOptionIsChecked) {
                $password[] = range('0', '9')[random_int(0, count(range('0', '9')) - 1)];
            }

            if ($specialCharacterOptionIsChecked) {
                $password[] = $specialCharacters[random_int(0, count($specialCharacters) - 1)];
            }
        }

        $this->secureShuffle($password);

        $password = implode('', $password);

        if (strlen($password) > $length) {
            $elementsToDelete = strlen($password) - $length;
            $password         = substr($password, 0, -$elementsToDelete);
        }

        return $this->render('pages/password.html.twig', compact('password'));
    }

    /**
     * @throws ExceptionAlias
     */
    private function secureShuffle(array &$arr)
    {
        $arr    = array_values($arr);
        $length = count($arr);
        for ($i = $length - 1; $i > 0; $i--) {
            $j       = random_int(0, $i);
            $temp    = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }
    }
}
