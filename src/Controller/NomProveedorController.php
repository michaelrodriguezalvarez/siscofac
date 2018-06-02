<?php

namespace App\Controller;

use App\Entity\NomProveedor;
use App\Form\NomProveedorType;
use App\Entity\NomProvincia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/nom/proveedor")
 */
class NomProveedorController extends Controller
{
    /**
     * @Route("/", name="nom_proveedor_index", methods="GET")
     */
    public function index(): Response
    {
        $nomProveedors = $this->getDoctrine()
            ->getRepository(NomProveedor::class)
            ->findAll();

        return $this->render('nom_proveedor/index.html.twig', ['nom_proveedors' => $nomProveedors]);
    }

    /**
     * @Route("/new", name="nom_proveedor_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $nomProveedor = new NomProveedor();
        $form = $this->createForm(NomProveedorType::class, $nomProveedor);
        //Seteo manual del campo provincia
        /*if ($request->request->get("nom_proveedor") != null){
            $provincia = $this->getDoctrine()->getRepository(NomProvincia::class)->find($request->request->get("nom_proveedor")["provincia"]);
            $request->request->set($request->request->get("nom_proveedor")["provincia"],$provincia);
            var_dump($request->request->get("nom_proveedor"));            
        }*/
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nomProveedor);
            $em->flush();

            return $this->redirectToRoute('nom_proveedor_index');
        }

        return $this->render('nom_proveedor/new.html.twig', [
            'nom_proveedor' => $nomProveedor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_proveedor_show", methods="GET")
     */
    public function show(NomProveedor $nomProveedor): Response
    {
        return $this->render('nom_proveedor/show.html.twig', ['nom_proveedor' => $nomProveedor]);
    }

    /**
     * @Route("/{id}/edit", name="nom_proveedor_edit", methods="GET|POST")
     */
    public function edit(Request $request, NomProveedor $nomProveedor): Response
    {
        $form = $this->createForm(NomProveedorType::class, $nomProveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nom_proveedor_edit', ['id' => $nomProveedor->getId()]);
        }

        return $this->render('nom_proveedor/edit.html.twig', [
            'nom_proveedor' => $nomProveedor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="nom_proveedor_delete", methods="DELETE")
     */
    public function delete(Request $request, NomProveedor $nomProveedor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nomProveedor->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nomProveedor);
            $em->flush();
        }

        return $this->redirectToRoute('nom_proveedor_index');
    }

    /**
     * @Route("/new_modal", name="nom_proveedor_new_modal", methods="GET|POST")
     */
    public function new_modal(Request $request): Response
    {
        $nomProveedor = new NomProveedor();
        $form = $this->createForm(NomProveedorType::class, $nomProveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nomProveedor);
            $em->flush();

            return $this->redirectToRoute('nom_proveedor_new');
        }

        return $this->render('nom_proveedor/new_modal.html.twig', [
            'nom_proveedor' => $nomProveedor,
            'form' => $form->createView(),
        ]);
    }
}
