<?php

namespace App\Controller;

use App\Entity\Acuerdo;
use App\Form\AcuerdoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/acuerdo")
 */
class AcuerdoController extends Controller
{
    /**
     * @Route("/", name="acuerdo_index", methods="GET")
     */
    public function index(): Response
    {
        $acuerdos = $this->getDoctrine()
            ->getRepository(Acuerdo::class)
            ->findAll();

        return $this->render('acuerdo/index.html.twig', ['acuerdos' => $acuerdos]);
    }

    /**
     * @Route("/new", name="acuerdo_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $acuerdo = new Acuerdo();
        $form = $this->createForm(AcuerdoType::class, $acuerdo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($acuerdo);
            $em->flush();

            return $this->redirectToRoute('acuerdo_index');
        }

        return $this->render('acuerdo/new.html.twig', [
            'acuerdo' => $acuerdo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="acuerdo_show", methods="GET")
     */
    public function show(Acuerdo $acuerdo): Response
    {
        return $this->render('acuerdo/show.html.twig', ['acuerdo' => $acuerdo]);
    }

    /**
     * @Route("/{id}/edit", name="acuerdo_edit", methods="GET|POST")
     */
    public function edit(Request $request, Acuerdo $acuerdo): Response
    {
        $form = $this->createForm(AcuerdoType::class, $acuerdo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('acuerdo_edit', ['id' => $acuerdo->getId()]);
        }

        return $this->render('acuerdo/edit.html.twig', [
            'acuerdo' => $acuerdo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="acuerdo_delete", methods="DELETE")
     */
    public function delete(Request $request, Acuerdo $acuerdo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$acuerdo->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($acuerdo);
            $em->flush();
        }

        return $this->redirectToRoute('acuerdo_index');
    }
}
