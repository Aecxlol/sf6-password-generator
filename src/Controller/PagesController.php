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
        return $this->render('pages/home.html.twig', [
            'password_min_length' => $this->getParameter('app.password_min_length'),
            'password_max_length' => $this->getParameter('app.password_max_length'),
            'password_default_length' => $this->getParameter('app.password_default_length')
        ]);
    }

    /**
     * @param Request $request
     * @param PasswordGenerator $passwordGenerator
     * @return Response
     * @throws ExceptionAlias
     */
    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request, PasswordGenerator $passwordGenerator): Response
    {
        # Saving all the user's choices from the form
        $length = max(min($request->query->getInt('length'), $this->getParameter('app.password_max_length')), $this->getParameter('app.password_min_length'));
        $uppercaseLetters = $request->query->getBoolean('uppercase-letters');
        $digits = $request->query->getBoolean('digits');
        $specialCharacters = $request->query->getBoolean('special-characters');

        # We make sure that the password length is always
        # < 60 {app.password_max_length}
        # > 8 {app.password_min_length}
        $password = $passwordGenerator->generate(
            length: $length,
            uppercaseLetterOptionIsChecked: $uppercaseLetters,
            digitOptionIsChecked: $digits,
            specialCharacterOptionIsChecked: $specialCharacters
        );

        return $this->render('pages/password.html.twig', compact('password'));
    }
}
