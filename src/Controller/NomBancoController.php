<?php

namespace App\Controller;

use App\Entity\NomBanco;
use App\Form\NomBancoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/nom/banco")
 */
class NomBancoController extends Controller
{
    /**
     * @Route("/", name="nom_banco_index", methods="GET")
     */
    public function index(): Response
    {
        $nomBancos = $this->getDoctrine()
            ->getRepository(NomBanco::class)
            ->findAll();

        return $this->render('nom_banco/index.html.twig', ['nom_bancos' => $nomBancos]);
    }

    /**
     * @Route("/new", name="nom_banco_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $nomBanco = new NomBanco();
        $form = $this->createForm(NomBancoType::class, $nomBanco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nomBanco);
            $em->flush();

            return $this->redirectToRoute('nom_banco_index');
        }

        return $this->render('nom_banco/new.html.twig', [
            'nom_banco' => $nomBanco,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_banco_show", methods="GET")
     */
    public function show(NomBanco $nomBanco): Response
    {
        return $this->render('nom_banco/show.html.twig', ['nom_banco' => $nomBanco]);
    }

    /**
     * @Route("/{id}/edit", name="nom_banco_edit", methods="GET|POST")
     */
    public function edit(Request $request, NomBanco $nomBanco): Response
    {
        $form = $this->createForm(NomBancoType::class, $nomBanco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nom_banco_edit', ['id' => $nomBanco->getId()]);
        }

        return $this->render('nom_banco/edit.html.twig', [
            'nom_banco' => $nomBanco,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_banco_delete", methods="DELETE")
     */
    public function delete(Request $request, NomBanco $nomBanco): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nomBanco->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nomBanco);
            $em->flush();
        }

        return $this->redirectToRoute('nom_banco_index');
    }
}
