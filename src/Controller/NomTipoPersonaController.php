<?php

namespace App\Controller;

use App\Entity\NomTipoPersona;
use App\Form\NomTipoPersonaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/nom/tipo/persona")
 */
class NomTipoPersonaController extends Controller
{
    /**
     * @Route("/", name="nom_tipo_persona_index", methods="GET")
     */
    public function index(): Response
    {
        $nomTipoPersonas = $this->getDoctrine()
            ->getRepository(NomTipoPersona::class)
            ->findAll();

        return $this->render('nom_tipo_persona/index.html.twig', ['nom_tipo_personas' => $nomTipoPersonas]);
    }

    /**
     * @Route("/new", name="nom_tipo_persona_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $nomTipoPersona = new NomTipoPersona();
        $form = $this->createForm(NomTipoPersonaType::class, $nomTipoPersona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nomTipoPersona);
            $em->flush();

            return $this->redirectToRoute('nom_tipo_persona_index');
        }

        return $this->render('nom_tipo_persona/new.html.twig', [
            'nom_tipo_persona' => $nomTipoPersona,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_tipo_persona_show", methods="GET")
     */
    public function show(NomTipoPersona $nomTipoPersona): Response
    {
        return $this->render('nom_tipo_persona/show.html.twig', ['nom_tipo_persona' => $nomTipoPersona]);
    }

    /**
     * @Route("/{id}/edit", name="nom_tipo_persona_edit", methods="GET|POST")
     */
    public function edit(Request $request, NomTipoPersona $nomTipoPersona): Response
    {
        $form = $this->createForm(NomTipoPersonaType::class, $nomTipoPersona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nom_tipo_persona_edit', ['id' => $nomTipoPersona->getId()]);
        }

        return $this->render('nom_tipo_persona/edit.html.twig', [
            'nom_tipo_persona' => $nomTipoPersona,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_tipo_persona_delete", methods="DELETE")
     */
    public function delete(Request $request, NomTipoPersona $nomTipoPersona): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nomTipoPersona->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nomTipoPersona);
            $em->flush();
        }

        return $this->redirectToRoute('nom_tipo_persona_index');
    }
}
