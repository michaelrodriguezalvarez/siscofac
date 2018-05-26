<?php

namespace App\Controller;

use App\Entity\NomTipoServicio;
use App\Form\NomTipoServicioType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/nom/tipo/servicio")
 */
class NomTipoServicioController extends Controller
{
    /**
     * @Route("/", name="nom_tipo_servicio_index", methods="GET")
     */
    public function index(): Response
    {
        $nomTipoServicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->findAll();

        return $this->render('nom_tipo_servicio/index.html.twig', ['nom_tipo_servicios' => $nomTipoServicios]);
    }

    /**
     * @Route("/new", name="nom_tipo_servicio_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $nomTipoServicio = new NomTipoServicio();
        $form = $this->createForm(NomTipoServicioType::class, $nomTipoServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nomTipoServicio);
            $em->flush();

            return $this->redirectToRoute('nom_tipo_servicio_index');
        }

        return $this->render('nom_tipo_servicio/new.html.twig', [
            'nom_tipo_servicio' => $nomTipoServicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_tipo_servicio_show", methods="GET")
     */
    public function show(NomTipoServicio $nomTipoServicio): Response
    {
        return $this->render('nom_tipo_servicio/show.html.twig', ['nom_tipo_servicio' => $nomTipoServicio]);
    }

    /**
     * @Route("/{id}/edit", name="nom_tipo_servicio_edit", methods="GET|POST")
     */
    public function edit(Request $request, NomTipoServicio $nomTipoServicio): Response
    {
        $form = $this->createForm(NomTipoServicioType::class, $nomTipoServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nom_tipo_servicio_edit', ['id' => $nomTipoServicio->getId()]);
        }

        return $this->render('nom_tipo_servicio/edit.html.twig', [
            'nom_tipo_servicio' => $nomTipoServicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_tipo_servicio_delete", methods="DELETE")
     */
    public function delete(Request $request, NomTipoServicio $nomTipoServicio): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nomTipoServicio->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nomTipoServicio);
            $em->flush();
        }

        return $this->redirectToRoute('nom_tipo_servicio_index');
    }
}
