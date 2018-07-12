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
        //array("Titulo"=>"Contenido")
        $consejos = array(
            array("Titulo"=>"Eliminar Contrato", "Contenido"=>"Los contratos una vez creados no pueden ser eliminados"),
            array("Titulo"=>"Eliminar Factura", "Contenido"=>"Las facturas una vez creadas no pueden ser eliminadas"),
        );
        $seleccion = mt_rand(0, count($consejos)-1);
        return new Response(
            "<h4>".$consejos[$seleccion]["Titulo"]."</h4>".
            "<p>".$consejos[$seleccion]["Contenido"]."</p>"
        );
    }
}
