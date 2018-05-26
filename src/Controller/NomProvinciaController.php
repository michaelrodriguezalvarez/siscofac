<?php

namespace App\Controller;

use App\Entity\NomProvincia;
use App\Form\NomProvinciaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/nom/provincia")
 */
class NomProvinciaController extends Controller
{
    /**
     * @Route("/", name="nom_provincia_index", methods="GET")
     */
    public function index(): Response
    {
        $nomProvincias = $this->getDoctrine()
            ->getRepository(NomProvincia::class)
            ->findAll();

        return $this->render('nom_provincia/index.html.twig', ['nom_provincias' => $nomProvincias]);
    }

    /**
     * @Route("/new", name="nom_provincia_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $nomProvincium = new NomProvincia();
        $form = $this->createForm(NomProvinciaType::class, $nomProvincium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nomProvincium);
            $em->flush();

            return $this->redirectToRoute('nom_provincia_index');
        }

        return $this->render('nom_provincia/new.html.twig', [
            'nom_provincium' => $nomProvincium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_provincia_show", methods="GET")
     */
    public function show(NomProvincia $nomProvincium): Response
    {
        return $this->render('nom_provincia/show.html.twig', ['nom_provincium' => $nomProvincium]);
    }

    /**
     * @Route("/{id}/edit", name="nom_provincia_edit", methods="GET|POST")
     */
    public function edit(Request $request, NomProvincia $nomProvincium): Response
    {
        $form = $this->createForm(NomProvinciaType::class, $nomProvincium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nom_provincia_edit', ['id' => $nomProvincium->getId()]);
        }

        return $this->render('nom_provincia/edit.html.twig', [
            'nom_provincium' => $nomProvincium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_provincia_delete", methods="DELETE")
     */
    public function delete(Request $request, NomProvincia $nomProvincium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nomProvincium->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nomProvincium);
            $em->flush();
        }

        return $this->redirectToRoute('nom_provincia_index');
    }
}
