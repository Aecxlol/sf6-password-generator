<?php

namespace App\Controller;

use Exception as ExceptionAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
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
            $password[] = $this->pickRandomItemFromARangeOfCharacters(range('a', 'z'));

            if ($uppercaseLetterOptionIsChecked) {
                $password[] = $this->pickRandomItemFromARangeOfCharacters(range('A', 'Z'));
            }

            if ($numberOptionIsChecked) {
                $password[] = $this->pickRandomItemFromARangeOfCharacters(range('0', '9'));;
            }

            if ($specialCharacterOptionIsChecked) {
                $password[] = $this->pickRandomItemFromARangeOfCharacters($specialCharacters);;
            }
        }

        $password = $this->secureShuffle($password);

        $password = implode('', $password);

        if (strlen($password) > $length) {
            $elementsToDelete = strlen($password) - $length;
            $password         = substr($password, 0, -$elementsToDelete);
        }

        return $this->render('pages/password.html.twig', compact('password'));
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
