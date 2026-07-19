<?php

namespace App\Identity\User\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserLoginController extends AbstractController
{
    public function login(): Response
    {
        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}