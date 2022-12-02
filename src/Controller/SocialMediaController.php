<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SocialMediaController extends AbstractController
{



    #[Route('/socialmedia', name: 'social_media')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}
