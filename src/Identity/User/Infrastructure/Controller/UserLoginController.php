<?php

namespace App\Identity\User\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class UserLoginController extends AbstractController
{
    #[Route('/login', name: 'user-login', methods: ['POST'])]
    public function login(): void
    {
    }
}