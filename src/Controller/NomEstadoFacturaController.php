<?php

namespace App\Controller;

use App\Entity\NomEstadoFactura;
use App\Form\NomEstadoFacturaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/nom/estado/factura")
 */
class NomEstadoFacturaController extends Controller
{
    /**
     * @Route("/", name="nom_estado_factura_index", methods="GET")
     */
    public function index(): Response
    {
        $nomEstadoFacturas = $this->getDoctrine()
            ->getRepository(NomEstadoFactura::class)
            ->findAll();

        return $this->render('nom_estado_factura/index.html.twig', ['nom_estado_facturas' => $nomEstadoFacturas]);
    }

    /**
     * @Route("/new", name="nom_estado_factura_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $nomEstadoFactura = new NomEstadoFactura();
        $form = $this->createForm(NomEstadoFacturaType::class, $nomEstadoFactura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nomEstadoFactura);
            $em->flush();

            return $this->redirectToRoute('nom_estado_factura_index');
        }

        return $this->render('nom_estado_factura/new.html.twig', [
            'nom_estado_factura' => $nomEstadoFactura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_estado_factura_show", methods="GET")
     */
    public function show(NomEstadoFactura $nomEstadoFactura): Response
    {
        return $this->render('nom_estado_factura/show.html.twig', ['nom_estado_factura' => $nomEstadoFactura]);
    }

    /**
     * @Route("/{id}/edit", name="nom_estado_factura_edit", methods="GET|POST")
     */
    public function edit(Request $request, NomEstadoFactura $nomEstadoFactura): Response
    {
        $form = $this->createForm(NomEstadoFacturaType::class, $nomEstadoFactura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nom_estado_factura_edit', ['id' => $nomEstadoFactura->getId()]);
        }

        return $this->render('nom_estado_factura/edit.html.twig', [
            'nom_estado_factura' => $nomEstadoFactura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_estado_factura_delete", methods="DELETE")
     */
    public function delete(Request $request, NomEstadoFactura $nomEstadoFactura): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nomEstadoFactura->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nomEstadoFactura);
            $em->flush();
        }

        return $this->redirectToRoute('nom_estado_factura_index');
    }
}
