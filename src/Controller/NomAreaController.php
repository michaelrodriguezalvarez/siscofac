<?php

namespace App\Controller;

use App\Entity\NomArea;
use App\Form\NomAreaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/nom/area")
 */
class NomAreaController extends Controller
{
    /**
     * @Route("/", name="nom_area_index", methods="GET")
     */
    public function index(): Response
    {
        $nomAreas = $this->getDoctrine()
            ->getRepository(NomArea::class)
            ->findAll();

        return $this->render('nom_area/index.html.twig', ['nom_areas' => $nomAreas]);
    }

    /**
     * @Route("/new", name="nom_area_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $nomArea = new NomArea();
        $form = $this->createForm(NomAreaType::class, $nomArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nomArea);
            $em->flush();

            return $this->redirectToRoute('nom_area_index');
        }

        return $this->render('nom_area/new.html.twig', [
            'nom_area' => $nomArea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_area_show", methods="GET")
     */
    public function show(NomArea $nomArea): Response
    {
        return $this->render('nom_area/show.html.twig', ['nom_area' => $nomArea]);
    }

    /**
     * @Route("/{id}/edit", name="nom_area_edit", methods="GET|POST")
     */
    public function edit(Request $request, NomArea $nomArea): Response
    {
        $form = $this->createForm(NomAreaType::class, $nomArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nom_area_edit', ['id' => $nomArea->getId()]);
        }

        return $this->render('nom_area/edit.html.twig', [
            'nom_area' => $nomArea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_area_delete", methods="DELETE")
     */
    public function delete(Request $request, NomArea $nomArea): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nomArea->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nomArea);
            $em->flush();
        }

        return $this->redirectToRoute('nom_area_index');
    }
}
