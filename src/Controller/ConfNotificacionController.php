<?php

namespace App\Controller;

use App\Entity\ConfNotificacion;
use App\Form\ConfNotificacionType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Twig\ConfNotificacionExtension;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ConfNotificacionController extends Controller
{
    /**
     * @Route("/conf/notificacion", name="conf_notificacion")
     */
    public function index(Request $request)
    {
        $configuracion_notificacion = new ConfNotificacion();
        $configuracion_notificacion_guardado = $this->getDoctrine()
            ->getRepository(ConfNotificacion::class)
            ->findAll();
        if(count($configuracion_notificacion_guardado)>0)
        {
            $configuracion_notificacion = clone $configuracion_notificacion_guardado[0];
        }else{
            $configuracion_notificacion->setCorreoNombre("SISCOFAC");
            $configuracion_notificacion->setCorreoDireccion("admin@localhost");
            $configuracion_notificacion->setCorreoServidor("localhost");
            $configuracion_notificacion->setCorreoPuerto(25);
            $configuracion_notificacion->setCorreoAsunto("Notificación");
            $configuracion_notificacion->setCorreoTexto(
                                "Cordiales saludos.\nSe le notifica que el contrato --identificador-- ha sido inhabilitado por el siguiente motivo: --motivo--.\nAtentamente, el administrador de --aplicacion--.");
            $configuracion_notificacion->setDiasMinimoNotificacion(30);
            $configuracion_notificacion->setSaldoMinimoNotificacionCup(100);
            $configuracion_notificacion->setSaldoMinimoNotificacionCuc(100);
        }

        $form = $this->createForm(ConfNotificacionType::class, $configuracion_notificacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(count($configuracion_notificacion_guardado)>0){
                $em->remove($configuracion_notificacion_guardado[0]);
            }
            $em->persist($configuracion_notificacion);
            $em->flush();
            $this->addFlash(
                'notice',
                'Los datos fueron guardados satisfactoriamente'
            );
            return $this->redirectToRoute('conf_notificacion');
        }

        return $this->render('conf_notificacion/index.html.twig', [
            'configuracion_notificacion' => $configuracion_notificacion,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/conf/notificacion/enviar/correo", name="conf_notificacion_enviar_correo")
     */
    public function enviar_correo(Request $request,RegistryInterface $doctrine):Response
    {
        $confNotificacionExtension = new ConfNotificacionExtension($doctrine);
        $respuesta = $confNotificacionExtension->enviarCorreo(array('michael@localhost' => 'Michael'),5452,"Inhabilitado","SISCOFAC");
        return new Response($respuesta);
    }
}
