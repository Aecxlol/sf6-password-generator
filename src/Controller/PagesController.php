<?php

namespace App\Controller;

use App\Service\PasswordGenerator;
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
        $passwordGenerator = new PasswordGenerator();
        $password          = $passwordGenerator->generate(
            length: $request->query->getInt('length'),
            uppercaseLetterOptionIsChecked: $request->query->getBoolean('uppercase-letters'),
            numberOptionIsChecked: $request->query->getBoolean('numbers'),
            specialCharacterOptionIsChecked: $request->query->getBoolean('special-characters')
        );

        return $this->render('pages/password.html.twig', compact('password'));
    }
}
