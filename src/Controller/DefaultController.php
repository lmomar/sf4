<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class DefaultController
{

    public function index(Environment $twig){
        return new Response($twig->render('home/index.html.twig'));
    }
}