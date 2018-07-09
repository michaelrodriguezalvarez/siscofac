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
use App\Entity\ContratoComiteContratacion;
use App\Form\ContratoComiteContratacionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Twig\ConfNotificacionExtension;

/**
 * @Route("/contrato/comite/contratacion")
 */
class ContratoComiteContratacionController extends Controller
{
    /**
     * @Route("/", name="contrato_comite_contratacion_index", methods="GET")
     */
    public function index(): Response
    {
        $contratoComiteContratacions = $this->getDoctrine()
            ->getRepository(ContratoComiteContratacion::class)
            ->getDatosParaListar();

        return $this->render('contrato_comite_contratacion/index.html.twig', ['contrato_comite_contratacions' => $contratoComiteContratacions]);
    }

    /**
     * @Route("/new", name="contrato_comite_contratacion_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $proveedores = $this->getDoctrine()
            ->getRepository(NomProveedor::class)
            ->getParsedFieldFromSelect();
        $tipos_de_servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->getParsedFieldFromSelect();
        $areas_administra_contrato = $this->getDoctrine()
            ->getRepository(NomArea::class)
            ->getParsedFieldFromSelect();
        $orden = $this->getDoctrine()
            ->getRepository(ContratoComiteContratacion::class)
            ->getMaximoValorOrden();


        $contratoComiteContratacion = new ContratoComiteContratacion();
        $form = $this->createForm(ContratoComiteContratacionType::class, $contratoComiteContratacion, [
            'proveedores'=>$proveedores,
            'tipos_de_servicios'=>$tipos_de_servicios,
            'areas_administra_contrato'=>$areas_administra_contrato,
            'orden'=>$orden
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contratoComiteContratacion);
            $em->flush();

            return $this->redirectToRoute('contrato_comite_contratacion_index');
        }

        return $this->render('contrato_comite_contratacion/new.html.twig', [
            'contrato_comite_contratacion' => $contratoComiteContratacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contrato_comite_contratacion_show", methods="GET")
     */
    public function show(ContratoComiteContratacion $contratoComiteContratacion): Response
    {
        return $this->render('contrato_comite_contratacion/show.html.twig', ['contrato_comite_contratacion' => $contratoComiteContratacion]);
    }

    /**
     * @Route("/{id}/edit", name="contrato_comite_contratacion_edit", methods="GET|POST")
     */
    public function edit(Request $request, ContratoComiteContratacion $contratoComiteContratacion): Response
    {
        $proveedores = $this->getDoctrine()
            ->getRepository(NomProveedor::class)
            ->getParsedFieldFromSelect();
        $tipos_de_servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->getParsedFieldFromSelect();
        $areas_administra_contrato = $this->getDoctrine()
            ->getRepository(NomArea::class)
            ->getParsedFieldFromSelect();

        $form = $this->createForm(ContratoComiteContratacionType::class, $contratoComiteContratacion, [
            'proveedores'=>$proveedores,
            'tipos_de_servicios'=>$tipos_de_servicios,
            'areas_administra_contrato'=>$areas_administra_contrato,
            'orden'=>$contratoComiteContratacion->getOrden()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contrato_comite_contratacion_edit', ['id' => $contratoComiteContratacion->getId()]);
        }

        return $this->render('contrato_comite_contratacion/edit.html.twig', [
            'contrato_comite_contratacion' => $contratoComiteContratacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contrato_comite_contratacion_delete", methods="DELETE")
     */
    public function delete(Request $request, ContratoComiteContratacion $contratoComiteContratacion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contratoComiteContratacion->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contratoComiteContratacion);
            $em->flush();
        }

        return $this->redirectToRoute('contrato_comite_contratacion_index');
    }

    /**
     * @Route("/modificar/modal", name="contrato_comite_contratacion_modificar_modal", methods="GET|POST")
     */
    public function contrato_comite_contratacion_modificar_modal(Request $request): Response
    {
        return $this->render('contrato_comite_contratacion/modificar.html.twig');
    }
    /**
     * @Route("/modificar/modal/ajax", name="contrato_comite_contratacion_modificar_modal_ajax", methods="POST")
     */
    public function contrato_modificar_modal_ajax(Request $request): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $token = $request->request->get('_token');
            if ($this->isCsrfTokenValid('contrato_modificar_modal', $request->request->get('_token'))) {
                $numero_form_contrato_modificar_modal = $request->request->get('numero_form_contrato_modificar_modal');
                $proveedor_form_contrato_modificar_modal = $request->request->get('proveedor_form_contrato_modificar_modal');

                $resultado = array();

                if($numero_form_contrato_modificar_modal != ""){
                    if($proveedor_form_contrato_modificar_modal != ""){
                        //proceso los de alante y los de atras
                        $resultado = $this->getDoctrine()
                            ->getRepository(ContratoComiteContratacion::class)
                            ->getParsedFiltradosPorNumeroProveedor(
                                $numero_form_contrato_modificar_modal,
                                $proveedor_form_contrato_modificar_modal
                            );
                    }else{
                        //proceso los dos de alante y
                        $resultado = $this->getDoctrine()
                            ->getRepository(ContratoComiteContratacion::class)
                            ->getParsedFiltradosPorNumeroProveedor(
                                $numero_form_contrato_modificar_modal,
                                ""
                            );
                    }
                }else{
                    if($proveedor_form_contrato_modificar_modal != ""){
                        //proceso los dos de atras
                        $resultado = $this->getDoctrine()
                            ->getRepository(ContratoComiteContratacion::class)
                            ->getParsedFiltradosPorNumeroProveedor(
                                0,
                                $proveedor_form_contrato_modificar_modal
                            );
                    }else{
                        //error debe llenar campos
                        return new JsonResponse(array('data' => 'Es requerido completar los campos','encontrado'=>'No'));
                    }
                }

                try{
                    if(count($resultado)==1){
                        return new JsonResponse(array('data' => $resultado[0]['id'],'encontrado'=>'Si'));
                    }else{
                        return new JsonResponse(array('data' => 'No ha sido encontrado el contrato','encontrado'=>'No'));
                    }

                }catch (ORMInvalidArgumentException $orme){
                    return new JsonResponse(array('data' => $orme->getMessage(),'encontrado'=>'No'));
                }
            }else{
                //Ataque Csrf
                return new JsonResponse(array('data' => 'No es válida la forma en solicitar la respuesta del servidor1','encontrado'=>'No'));
            }
        }else{
            //No es ajax
            return new JsonResponse(array('data' => 'No es válida la forma en solicitar la respuesta del servidor2','encontrado'=>'No'));
        }
    }

    /**
     * @Route("/aprobar/{id}", name="contrato_comite_contratacion_aprobar", methods="GET|POST")
     */
    public function aprobar(int $id, Request $request, RegistryInterface $doctrine):Response{

        $session = $request->getSession();
        $session->set('escenario','contrato_new');

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

        $areas_administra_contrato = $this->getDoctrine()
            ->getRepository(NomArea::class)
            ->getParsedFieldFromSelect();

        $anno_actual = date('Y');
        $cantidad_de_contratos_del_anno_actual = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getCantidadContratosPorAnno($anno_actual);

        $contrato = new Contrato();

        $contratoComiteContratacion = $this->getDoctrine()->getRepository(ContratoComiteContratacion::class)->find($id);        $contrato = new Contrato();
        $contrato->setProveedor($contratoComiteContratacion->getProveedor());
        $contrato->setTipoDeServicio($contratoComiteContratacion->getTipoDeServicio());
        $contrato->setObjeto($contratoComiteContratacion->getObjeto());
        $contrato->setValorContratoInicialCup($contratoComiteContratacion->getValorContratoInicialCup());
        $contrato->setValorContratoInicialCuc($contratoComiteContratacion->getValorContratoInicialCuc());
        $contrato->setValorContratoTotalCup($contrato->getValorContratoInicialCup());
        $contrato->setValorContratoTotalCuc($contrato->getValorContratoInicialCuc());
        $contrato->setEjecucionContratoCup(0);
        $contrato->setEjecucionContratoCuc(0);
        $contrato->setSaldoCup($contrato->getValorContratoInicialCup());
        $contrato->setSaldoCuc($contrato->getValorContratoInicialCuc());

        $contrato->setAreaAdministraContrato($contratoComiteContratacion->getAreaAdministraContrato());

        $form = $this->createForm(ContratoType::class, $contrato,[
            "ultimos_annos_hasta_actual"=>$ultimos_annos_hasta_actual,
            'proveedores'=>$proveedores,
            'tipos_de_servicios'=>$tipos_de_servicios,
            'tipos_de_persona'=>$tipos_de_persona,
            'bancos'=>$bancos,
            'areas_administra_contrato'=>$areas_administra_contrato,
        ],
            array('action' => $this->generateUrl('contrato_comite_contratacion_aprobar',array('id'=>$id)))
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contratos_encontrados = $this->getDoctrine()
                ->getRepository(Contrato::class)
                ->getContratoPorNumeroYAnno($contrato->getNumero(), $contrato->getAnno());
            if (count($contratos_encontrados)==0){
                $em = $this->getDoctrine()->getManager();
                $em->persist($contrato);
                $em->remove($contratoComiteContratacion);
                $em->flush();
                $this->addFlash(
                    'notice',
                    'Los datos fueron guardados satisfactoriamente'
                );

                if($contrato->getEstado()==0){
                    $confNotificacionExtension = new ConfNotificacionExtension($doctrine);
                    $this->addFlash(
                        'notice',
                        $confNotificacionExtension->enviarCorreoNotificacion('definido_por_usuario', $contrato)
                    );
                }

                return $this->redirectToRoute('contrato_comite_contratacion_index');
            }else{
                $this->addFlash(
                    'notice',
                    'Debe especificar un número diferente al contrato'
                );
            }
        }

        return $this->render('contrato/new.html.twig', [
            'anno_actual'=>$anno_actual,
            'cantidad_de_contratos_del_anno_actual'=>$cantidad_de_contratos_del_anno_actual,
            'contrato' => $contrato,
            'form' => $form->createView(),
        ]);


    }

        /**
     * @Route("/denegar/{id}", name="contrato_comite_contratacion_denegar", methods="GET|POST")
     */
    public function denegar($id):Response{
        $contratoComiteContratacion = $this->getDoctrine()->getRepository(ContratoComiteContratacion::class)->find($id);
        if ($contratoComiteContratacion != null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($contratoComiteContratacion);
            $em->flush();
            $this->addFlash(
                'notice',
                'Contrato denegado satisfactoriamente'
            );
            return $this->redirectToRoute('contrato_comite_contratacion_index');
        }else{
            $this->addFlash(
                'notice',
                'Error no se pudo encontrar el registro'
            );
        }

    }
}
