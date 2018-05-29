<?php

namespace App\Controller;

use App\Entity\Factura;
use App\Form\FacturaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/factura")
 */
class FacturaController extends Controller
{
    /**
     * @Route("/", name="factura_index", methods="GET")
     */
    public function index(): Response
    {
        $facturas = $this->getDoctrine()
            ->getRepository(Factura::class)
            ->findAll();

        return $this->render('factura/index.html.twig', ['facturas' => $facturas]);
    }

    /**
     * @Route("/new", name="factura_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $factura = new Factura();
        $form = $this->createForm(FacturaType::class, $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($factura);
            $em->flush();

            return $this->redirectToRoute('factura_index');
        }

        return $this->render('factura/new.html.twig', [
            'factura' => $factura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factura_show", methods="GET")
     */
    public function show(Factura $factura): Response
    {
        return $this->render('factura/show.html.twig', ['factura' => $factura]);
    }

    /**
     * @Route("/{id}/edit", name="factura_edit", methods="GET|POST")
     */
    public function edit(Request $request, Factura $factura): Response
    {
        $form = $this->createForm(FacturaType::class, $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('factura_edit', ['id' => $factura->getId()]);
        }

        return $this->render('factura/edit.html.twig', [
            'factura' => $factura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factura_delete", methods="DELETE")
     */
    public function delete(Request $request, Factura $factura): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factura->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($factura);
            $em->flush();
        }

        return $this->redirectToRoute('factura_index');
    }
}
