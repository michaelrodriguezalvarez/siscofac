<?php

namespace App\Controller;

use App\Entity\Contrato;
use App\Entity\NomProveedor;
use App\Entity\NomTipoServicio;
use App\Entity\NomTipoPersona;
use App\Entity\NomBanco;
use App\Entity\Acuerdo;
use App\Entity\NomArea;
use App\Entity\NomProvincia;
use App\Form\ContratoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contrato")
 */
class ContratoController extends Controller
{
    /**
     * @Route("/", name="contrato_index", methods="GET")
     */
    public function index(): Response
    {
        //var_dump($this->getDoctrine()
        //    ->getRepository(Contrato::class)
        //    ->getDatosParaListar());
        $contratos = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->findAll();

        return $this->render('contrato/index.html.twig', ['contratos' => $contratos]);
    }

    /**
     * @Route("/new", name="contrato_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $ultimos_annos_hasta_actual = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getUltimosNAnnosHastaActual(10);

        $proveedores = $this->getDoctrine()
            ->getRepository(NomProveedor::class)
            ->getParsedFieldFromSelect();
        
        $tipos_de_servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->getParsedFieldFromSelect();

        $tipos_de_persona = $this->getDoctrine()
            ->getRepository(NomTipoPersona::class)
            ->getParsedFieldFromSelect();

        $bancos = $this->getDoctrine()
            ->getRepository(NomBanco::class)
            ->getParsedFieldFromSelect();

        $acuerdos = $this->getDoctrine()
            ->getRepository(Acuerdo::class)
            ->getParsedFieldFromSelect();

        $areas_administra_contrato = $this->getDoctrine()
            ->getRepository(NomArea::class)
            ->getParsedFieldFromSelect();

        $contrato = new Contrato();
        $form = $this->createForm(ContratoType::class, $contrato,[
            "ultimos_annos_hasta_actual"=>$ultimos_annos_hasta_actual,
            'proveedores'=>$proveedores,            
            'tipos_de_servicios'=>$tipos_de_servicios,
            'tipos_de_persona'=>$tipos_de_persona,
            'bancos'=>$bancos,
            'acuerdos'=>$acuerdos,
            'areas_administra_contrato'=>$areas_administra_contrato,
            ],
            array('action' => $this->generateUrl('contrato_new'))
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contrato);
            $em->flush();

            return $this->redirectToRoute('contrato_new');
        }

        return $this->render('contrato/new.html.twig', [
            'contrato' => $contrato,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contrato_show", methods="GET")
     */
    public function show(Contrato $contrato): Response
    {
        $estados = array( 1 =>'Activo' , 0 => 'Inactivo');
        $proveedor = $this->getDoctrine()->getRepository(NomProveedor::class)->findOneBy(array('id'=>$contrato->getProveedor()));
        $provincia = $this->getDoctrine()->getRepository(NomProvincia::class)->findOneBy(array('id'=>$proveedor->getProvincia()));
        $tipo_de_servicio = $this->getDoctrine()->getRepository(NomTipoServicio::class)->findOneBy(array('id'=>$contrato->getTipoDeServicio()));
        $tipo_de_persona = $this->getDoctrine()->getRepository(NomTipoPersona::class)->findOneBy(array('id'=>$contrato->getTipoDePersona()));
        $banco = $this->getDoctrine()->getRepository(NomBanco::class)->findOneBy(array('id'=>$contrato->getBanco()));
        $aprobContratoComiteContratacion = $this->getDoctrine()->getRepository(Acuerdo::class)->findOneBy(array('id'=>$contrato->getAprobContratoComiteContratacion()));
        $aprobContratoComiteAdministracion = $this->getDoctrine()->getRepository(Acuerdo::class)->findOneBy(array('id'=>$contrato->getAprobContratoComiteAdministracion()));
        $areaAdministraContrato = $this->getDoctrine()->getRepository(NomArea::class)->findOneBy(array('id'=>$contrato->getAreaAdministraContrato()));

        return $this->render('contrato/show.html.twig', [
            'contrato' => $contrato,
            'proveedor' => $proveedor->getNombre(),
            'provincia' => $provincia->getNombre(),
            'tipo_de_servicio' => $tipo_de_servicio->getNombre(),
            'tipo_de_persona' => $tipo_de_persona->getNombre(),
            'banco' => $banco->getNombre(),
            'aprobContratoComiteContratacion' => $aprobContratoComiteContratacion,
            'aprobContratoComiteAdministracion' => $aprobContratoComiteAdministracion,
            'areaAdministraContrato' => $areaAdministraContrato->getNombre(),
            'estado' => $estados[$contrato->getEstado()]
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contrato_edit", methods="GET|POST")
     */
    public function edit(Request $request, Contrato $contrato): Response
    {
        $ultimos_annos_hasta_actual = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getUltimosNAnnosHastaActual(10);

        $proveedores = $this->getDoctrine()
            ->getRepository(NomProveedor::class)
            ->getParsedFieldFromSelect();

        $tipos_de_servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->getParsedFieldFromSelect();

        $tipos_de_persona = $this->getDoctrine()
            ->getRepository(NomTipoPersona::class)
            ->getParsedFieldFromSelect();

        $bancos = $this->getDoctrine()
            ->getRepository(NomBanco::class)
            ->getParsedFieldFromSelect();

        $acuerdos = $this->getDoctrine()
            ->getRepository(Acuerdo::class)
            ->getParsedFieldFromSelect();

        $areas_administra_contrato = $this->getDoctrine()
            ->getRepository(NomArea::class)
            ->getParsedFieldFromSelect();

        $form = $this->createForm(ContratoType::class, $contrato, [
        "ultimos_annos_hasta_actual"=>$ultimos_annos_hasta_actual,
            'proveedores'=>$proveedores,
            'tipos_de_servicios'=>$tipos_de_servicios,
            'tipos_de_persona'=>$tipos_de_persona,
            'bancos'=>$bancos,
            'acuerdos'=>$acuerdos,
            'areas_administra_contrato'=>$areas_administra_contrato,
            ],
            array('action' => $this->generateUrl('contrato_edit',['id' => $contrato->getId()])));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contrato_edit', ['id' => $contrato->getId()]);
        }

        return $this->render('contrato/edit.html.twig', [
            'contrato' => $contrato,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contrato_delete", methods="DELETE")
     */
    public function delete(Request $request, Contrato $contrato): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrato->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contrato);
            $em->flush();
        }

        return $this->redirectToRoute('contrato_index');
    }
}
