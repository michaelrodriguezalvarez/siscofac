<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PrincipalController extends Controller
{
    /**
     * @Route("/principal", name="principal")
     */
    public function index()
    {
        return $this->render('principal/index.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }

    /**
     * @Route("/", name="redireccion_inicio")
     */
    public function redireccion_inicio()
    {
        return $this->redirectToRoute('principal');
    }

    public function ayuda_inicio()
    {
        return new Response("<p>Hola mundo</p>");
    }
}
