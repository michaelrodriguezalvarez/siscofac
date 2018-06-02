<?php

namespace App\Controller;

use App\Entity\Suplemento;
use App\Form\SuplementoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/suplemento")
 */
class SuplementoController extends Controller
{
    /**
     * @Route("/", name="suplemento_index", methods="GET")
     */
    public function index(): Response
    {
        $suplementos = $this->getDoctrine()
            ->getRepository(Suplemento::class)
            ->findAll();

        return $this->render('suplemento/index.html.twig', ['suplementos' => $suplementos]);
    }

    /**
     * @Route("/new", name="suplemento_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $suplemento = new Suplemento();
        $form = $this->createForm(SuplementoType::class, $suplemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($suplemento);
            $em->flush();

            return $this->redirectToRoute('suplemento_index');
        }

        return $this->render('suplemento/new.html.twig', [
            'suplemento' => $suplemento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="suplemento_show", methods="GET")
     */
    public function show(Suplemento $suplemento): Response
    {
        return $this->render('suplemento/show.html.twig', ['suplemento' => $suplemento]);
    }

    /**
     * @Route("/{id}/edit", name="suplemento_edit", methods="GET|POST")
     */
    public function edit(Request $request, Suplemento $suplemento): Response
    {
        $form = $this->createForm(SuplementoType::class, $suplemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('suplemento_edit', ['id' => $suplemento->getId()]);
        }

        return $this->render('suplemento/edit.html.twig', [
            'suplemento' => $suplemento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="suplemento_delete", methods="DELETE")
     */
    public function delete(Request $request, Suplemento $suplemento): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suplemento->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($suplemento);
            $em->flush();
        }

        return $this->redirectToRoute('suplemento_index');
    }
}
