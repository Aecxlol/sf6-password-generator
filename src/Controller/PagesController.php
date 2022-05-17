<?php

namespace App\Controller;

use App\Service\PasswordGenerator;
use Exception as ExceptionAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        # Get the param set in services.yaml
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
    #[Route('/generate-password', name: 'app_generate_password', methods: ['GET'])]
    public function generatePassword(Request $request, PasswordGenerator $passwordGenerator): Response
    {
        # Saving all the user's choices from the form
        $length            = max(min($request->query->getInt('length'), $this->getParameter('app.password_max_length')), $this->getParameter('app.password_min_length'));
        $uppercaseLetters  = $request->query->getBoolean('uppercase-letters');
        $digits            = $request->query->getBoolean('digits');
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

        $response = $this->render('pages/password.html.twig', compact('password'));

        $this->setPreferencesAsCookies($response, $length, $uppercaseLetters, $digits, $specialCharacters);

        return $response;
    }

    /**
     * @param Response $response
     * @param int $length
     * @param bool $uppercaseLetters
     * @param bool $digits
     * @param bool $specialCharacters
     * @return void
     */
    private function setPreferencesAsCookies(Response $response, int $length, bool $uppercaseLetters, bool $digits, bool $specialCharacters): void
    {
        $fiveYearsFromNow = new \DateTimeImmutable('+5 years');

        $response->headers->setCookie(new Cookie('app_length', $length, $fiveYearsFromNow));
        $response->headers->setCookie(new Cookie('app_uppercase_letters', $uppercaseLetters ?: '0', $fiveYearsFromNow));
        $response->headers->setCookie(new Cookie('app_digits', $digits ?: '0', $fiveYearsFromNow));
        $response->headers->setCookie(new Cookie('app_special_characters', $specialCharacters ?: '0', $fiveYearsFromNow));
    }
}
