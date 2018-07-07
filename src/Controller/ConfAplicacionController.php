<?php

namespace App\Controller;

use App\Twig\ConfAplicacionExtension;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\ConfAplicacion;
use App\Form\ConfAplicacionType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfAplicacionController extends Controller
{
    /**
     * @Route("/conf/aplicacion", name="conf_aplicacion")
     */
    public function index(Request $request, RegistryInterface $doctrine):Response
    {
        $configuracion_aplicacion = new ConfAplicacion();
        $configuracion_aplicacion_guardado = $this->getDoctrine()
            ->getRepository(ConfAplicacion::class)
            ->findAll();
        if(count($configuracion_aplicacion_guardado)>0)
        {
            $configuracion_aplicacion = clone $configuracion_aplicacion_guardado[0];
        }else{
            $confAplicacionExtension = new ConfAplicacionExtension($doctrine);
            $configuracion_aplicacion->setNombreAplicacion($confAplicacionExtension->getNombreAplicacion());
            $configuracion_aplicacion->setSlogan($confAplicacionExtension->getSlogan());
            $configuracion_aplicacion->setIdiomaPorDefecto($confAplicacionExtension->getIdiomaPorDefecto());
        }

        $form = $this->createForm(ConfAplicacionType::class, $configuracion_aplicacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(count($configuracion_aplicacion_guardado)>0){
                $em->remove($configuracion_aplicacion_guardado[0]);
            }
            $em->persist($configuracion_aplicacion);
            $em->flush();
            $this->addFlash(
                'notice',
                'Los datos fueron guardados satisfactoriamente'
            );
            return $this->redirectToRoute('conf_aplicacion');
        }

        return $this->render('conf_aplicacion/index.html.twig', [
            'configuracion_aplicacion' => $configuracion_aplicacion,
            'form' => $form->createView(),
        ]);
    }
}
