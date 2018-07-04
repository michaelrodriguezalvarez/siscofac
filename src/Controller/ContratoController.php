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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $contratos_para_listar = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getDatosParaListar();

        return $this->render('contrato/index.html.twig', ['contratos_para_listar'=>$contratos_para_listar]);
    }

    /**
     * @Route("/new", name="contrato_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
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
        $form = $this->createForm(ContratoType::class, $contrato,[
            "ultimos_annos_hasta_actual"=>$ultimos_annos_hasta_actual,
            'proveedores'=>$proveedores,            
            'tipos_de_servicios'=>$tipos_de_servicios,
            'tipos_de_persona'=>$tipos_de_persona,
            'bancos'=>$bancos,
            'areas_administra_contrato'=>$areas_administra_contrato,
            ],
            array('action' => $this->generateUrl('contrato_new'))
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contratos_encontrados = $this->getDoctrine()
                ->getRepository(Contrato::class)
                ->getContratoPorNumeroYAnno($contrato->getNumero(), $contrato->getAnno());
            if (count($contratos_encontrados)==0){
                $em = $this->getDoctrine()->getManager();
                $em->persist($contrato);
                $em->flush();
                $this->addFlash(
                    'notice',
                    'Los datos fueron guardados satisfactoriamente'
                );
                return $this->redirectToRoute('contrato_new');
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
     * @Route("/{id}/{destino}/show", name="contrato_show", methods="GET")
     */
    public function show(Contrato $contrato, string $destino): Response
    {
        $estados = array( 1 =>'Activo' , 0 => 'Inactivo');
        $proveedor = $this->getDoctrine()->getRepository(NomProveedor::class)->findOneBy(array('id'=>$contrato->getProveedor()));
        $provincia = $this->getDoctrine()->getRepository(NomProvincia::class)->findOneBy(array('id'=>$proveedor->getProvincia()));
        $tipo_de_servicio = $this->getDoctrine()->getRepository(NomTipoServicio::class)->findOneBy(array('id'=>$contrato->getTipoDeServicio()));
        $tipo_de_persona = $this->getDoctrine()->getRepository(NomTipoPersona::class)->findOneBy(array('id'=>$contrato->getTipoDePersona()));
        $banco = $this->getDoctrine()->getRepository(NomBanco::class)->findOneBy(array('id'=>$contrato->getBanco()));
        $areaAdministraContrato = $this->getDoctrine()->getRepository(NomArea::class)->findOneBy(array('id'=>$contrato->getAreaAdministraContrato()));
        $cantidad_suplementos = $this->getDoctrine()->getRepository(Contrato::class)->getCantidadSuplentosDadoIdContrato($contrato->getId());
        $cantidad_facturas = $this->getDoctrine()->getRepository(Contrato::class)->getCantidadFacturasDadoIdContrato($contrato->getId());
        return $this->render('contrato/show.html.twig', [
            'id_contrato'=>$contrato->getId(),
            'contrato' => $contrato,
            'proveedor' => $proveedor->getNombre(),
            'provincia' => $provincia->getNombre(),
            'tipo_de_servicio' => $tipo_de_servicio->getNombre(),
            'tipo_de_persona' => $tipo_de_persona->getNombre(),
            'banco' => $banco->getNombre(),
            'areaAdministraContrato' => $areaAdministraContrato->getNombre(),
            'estado' => $estados[$contrato->getEstado()],
            'destino'=>$destino,
            'cantidad_suplementos'=>$cantidad_suplementos,
            'cantidad_facturas' => $cantidad_facturas
        ]);
    }

    /**
     * @Route("/edit/{id}", name="contrato_edit", methods="GET|POST")
     */
    public function edit(Request $request, Contrato $contrato): Response
    {
        $session = $request->getSession();
        $session->set('escenario','contrato_edit');
        $session->set('escenario_parametros',array('id'=>$contrato->getId()));

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

        $form = $this->createForm(ContratoType::class, $contrato, [
            'ultimos_annos_hasta_actual'=>$ultimos_annos_hasta_actual,
            'proveedores'=>$proveedores,
            'tipos_de_servicios'=>$tipos_de_servicios,
            'tipos_de_persona'=>$tipos_de_persona,
            'bancos'=>$bancos,
            'areas_administra_contrato'=>$areas_administra_contrato,
            ],
            array('action' => $this->generateUrl('contrato_edit',['id' => $contrato->getId()])));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'notice',
                'Los datos fueron guardados satisfactoriamente'
            );
            return $this->redirectToRoute('contrato_edit', ['id' => $contrato->getId()]);
        }

        return $this->render('contrato/edit.html.twig', [
            'anno_actual'=>$anno_actual,
            'cantidad_de_contratos_del_anno_actual'=>$cantidad_de_contratos_del_anno_actual,
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

            $cantidad_dependencias_contrato = $contratos_para_listar = $this->getDoctrine()
                ->getRepository(Contrato::class)
                ->getCantidadDependencias($contrato->getId());

            if ($cantidad_dependencias_contrato > 0){
                $this->addFlash(
                    'notice',
                    'El contrato tiene ejecución y no puede ser eliminado'
                );
                return $this->redirectToRoute('contrato_edit', ['id' => $contrato->getId()]);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($contrato);
            $em->flush();

        }

        return $this->redirectToRoute('contrato_new');
    }

    /**
     * @Route("/buscar/modal", name="contrato_buscar_modal", methods="GET|POST")
     */
    public function contrato_buscar_modal(Request $request): Response
    {
        $ultimos_annos_hasta_actual = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getUltimosNAnnosHastaActual(10);
        $servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->findAll();

        return $this->render('contrato/buscar.html.twig', [
            'ultimos_annos_hasta_actual'=>$ultimos_annos_hasta_actual,
            'servicios'=>$servicios,
        ]);
    }
    /**
     * @Route("/buscar/modal/ajax", name="contrato_buscar_modal_ajax", methods="POST")
     */
    public function contrato_buscar_modal_ajax(Request $request): JsonResponse
    {
            if ($request->isXMLHttpRequest()) {
                $token = $request->request->get('_token');
                if ($this->isCsrfTokenValid('contrato_buscar_modal', $request->request->get('_token'))) {
                    $numero_form_contrato_buscar_modal = $request->request->get('numero_form_contrato_buscar_modal');
                    $anno_form_contrato_buscar_modal = $request->request->get('anno_form_contrato_buscar_modal');
                    $proveedor_form_contrato_buscar_modal = $request->request->get('proveedor_form_contrato_buscar_modal');
                    $servicio_form_contrato_buscar_modal = $request->request->get('servicio_form_contrato_buscar_modal');

                    $resultado = array();

                    if($numero_form_contrato_buscar_modal != "" && $anno_form_contrato_buscar_modal != "No filtrar"){
                        if($proveedor_form_contrato_buscar_modal != "" && $servicio_form_contrato_buscar_modal != "No filtrar"){
                            //proceso los de alante y los de atras
                            $resultado = $this->getDoctrine()
                                ->getRepository(Contrato::class)
                                ->getParsedFiltradosPorNumeroAnnoProveedor(
                                    $numero_form_contrato_buscar_modal,
                                    $anno_form_contrato_buscar_modal,
                                    $proveedor_form_contrato_buscar_modal,
                                    $servicio_form_contrato_buscar_modal
                                );
                        }else{
                            //proceso los dos de alante y
                            $resultado = $this->getDoctrine()
                                ->getRepository(Contrato::class)
                                ->getParsedFiltradosPorNumeroAnnoProveedor(
                                    $numero_form_contrato_buscar_modal,
                                    $anno_form_contrato_buscar_modal,
                                    "",
                                    ""
                                );
                        }
                    }else{
                        if($proveedor_form_contrato_buscar_modal != "" && $servicio_form_contrato_buscar_modal != "No filtrar"){
                            //proceso los dos de atras
                            $resultado = $this->getDoctrine()
                                ->getRepository(Contrato::class)
                                ->getParsedFiltradosPorNumeroAnnoProveedor(
                                    0,
                                    0,
                                    $proveedor_form_contrato_buscar_modal,
                                    $servicio_form_contrato_buscar_modal
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
                }
                else{
                    //Ataque Csrf
                    return new JsonResponse(array('data' => 'No es válida la forma en solicitar la respuesta del servidor','encontrado'=>'No'));
                }
            }else{
                //No es ajax
                return new JsonResponse(array('data' => 'No es válida la forma en solicitar la respuesta del servidor','encontrado'=>'No'));
            }
    }

    /**
     * @Route("/modificar/modal", name="contrato_modificar_modal", methods="GET|POST")
     */
    public function contrato_modificar_modal(Request $request): Response
    {
        $ultimos_annos_hasta_actual = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getUltimosNAnnosHastaActual(10);
        $servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->findAll();

        return $this->render('contrato/modificar.html.twig', [
            'ultimos_annos_hasta_actual'=>$ultimos_annos_hasta_actual,
            'servicios'=>$servicios,
        ]);
    }
    /**
     * @Route("/modificar/modal/ajax", name="contrato_modificar_modal_ajax", methods="POST")
     */
    public function contrato_modificar_modal_ajax(Request $request): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $token = $request->request->get('_token');
            if ($this->isCsrfTokenValid('contrato_modificar_modal', $request->request->get('_token'))) {
                $numero_form_contrato_modificar_modal = $request->request->get('numero_form_contrato_modificar_modal');
                $anno_form_contrato_modificar_modal = $request->request->get('anno_form_contrato_modificar_modal');
                $proveedor_form_contrato_modificar_modal = $request->request->get('proveedor_form_contrato_modificar_modal');
                $servicio_form_contrato_modificar_modal = $request->request->get('servicio_form_contrato_modificar_modal');

                $resultado = array();

                if($numero_form_contrato_modificar_modal != "" && $anno_form_contrato_modificar_modal != "No filtrar"){
                    if($proveedor_form_contrato_modificar_modal != "" && $servicio_form_contrato_modificar_modal != "No filtrar"){
                        //proceso los de alante y los de atras
                        $resultado = $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->getParsedFiltradosPorNumeroAnnoProveedor(
                                $numero_form_contrato_modificar_modal,
                                $anno_form_contrato_modificar_modal,
                                $proveedor_form_contrato_modificar_modal,
                                $servicio_form_contrato_modificar_modal
                            );
                    }else{
                        //proceso los dos de alante y
                        $resultado = $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->getParsedFiltradosPorNumeroAnnoProveedor(
                                $numero_form_contrato_modificar_modal,
                                $anno_form_contrato_modificar_modal,
                                "",
                                ""
                            );
                    }
                }else{
                    if($proveedor_form_contrato_modificar_modal != "" && $servicio_form_contrato_modificar_modal != "No filtrar"){
                        //proceso los dos de atras
                        $resultado = $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->getParsedFiltradosPorNumeroAnnoProveedor(
                                0,
                                0,
                                $proveedor_form_contrato_modificar_modal,
                                $servicio_form_contrato_modificar_modal
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
     * @Route("/suplemento/modal", name="suplemento_modal", methods="GET|POST")
     */
    public function suplemento_modal(Request $request): Response
    {
        $ultimos_annos_hasta_actual = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getUltimosNAnnosHastaActual(10);
        $servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->findAll();

        return $this->render('contrato/suplemento.html.twig', [
            'ultimos_annos_hasta_actual'=>$ultimos_annos_hasta_actual,
            'servicios'=>$servicios,
        ]);
    }
    /**
     * @Route("/suplemento/modal/ajax", name="suplemento_modal_ajax", methods="POST")
     */
    public function suplemento_modal_ajax(Request $request): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $token = $request->request->get('_token');
            if ($this->isCsrfTokenValid('suplemento_modal', $request->request->get('_token_form_suplemento_modal'))) {
                $numero_form_suplemento_modal = $request->request->get('numero_form_suplemento_modal');
                $anno_form_suplemento_modal = $request->request->get('anno_form_suplemento_modal');
                $proveedor_form_suplemento_modal = $request->request->get('proveedor_form_suplemento_modal');
                $servicio_form_suplemento_modal = $request->request->get('servicio_form_suplemento_modal');

                $resultado = array();

                if($numero_form_suplemento_modal != "" && $anno_form_suplemento_modal != "No filtrar"){
                    if($proveedor_form_suplemento_modal != "" && $servicio_form_suplemento_modal != "No filtrar"){
                        //proceso los de alante y los de atras
                        $resultado = $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->getParsedFiltradosPorNumeroAnnoProveedor(
                                $numero_form_suplemento_modal,
                                $anno_form_suplemento_modal,
                                $proveedor_form_suplemento_modal,
                                $servicio_form_suplemento_modal
                            );
                    }else{
                        //proceso los dos de alante y
                        $resultado = $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->getParsedFiltradosPorNumeroAnnoProveedor(
                                $numero_form_suplemento_modal,
                                $anno_form_suplemento_modal,
                                "",
                                ""
                            );
                    }
                }else{
                    if($proveedor_form_suplemento_modal != "" && $servicio_form_suplemento_modal != "No filtrar"){
                        //proceso los dos de atras
                        $resultado = $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->getParsedFiltradosPorNumeroAnnoProveedor(
                                0,
                                0,
                                $proveedor_form_suplemento_modal,
                                $servicio_form_suplemento_modal
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
            }
            else{
                //Ataque Csrf
                return new JsonResponse(array('data' => 'No es válida la forma en solicitar la respuesta del servidor','encontrado'=>'No'));
            }
        }else{
            //No es ajax
            return new JsonResponse(array('data' => 'No es válida la forma en solicitar la respuesta del servidor','encontrado'=>'No'));
        }
    }

    /**
     * @Route("/factura/modal", name="factura_modal", methods="GET|POST")
     */
    public function factura_modal(Request $request): Response
    {
        $ultimos_annos_hasta_actual = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getUltimosNAnnosHastaActual(10);
        $servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->findAll();

        return $this->render('contrato/factura.html.twig', [
            'ultimos_annos_hasta_actual'=>$ultimos_annos_hasta_actual,
            'servicios'=>$servicios,
        ]);
    }
    /**
     * @Route("/factura/modal/ajax", name="factura_modal_ajax", methods="POST")
     */
    public function factura_modal_ajax(Request $request): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $token = $request->request->get('_token_form_factura_modal');
            if ($this->isCsrfTokenValid('factura_modal', $request->request->get('_token_form_factura_modal'))) {
                $numero_form_factura_modal = $request->request->get('numero_form_factura_modal');
                $anno_form_factura_modal = $request->request->get('anno_form_factura_modal');
                $proveedor_form_factura_modal = $request->request->get('proveedor_form_factura_modal');
                $servicio_form_factura_modal = $request->request->get('servicio_form_factura_modal');

                $resultado = array();

                if($numero_form_factura_modal != "" && $anno_form_factura_modal != "No filtrar"){
                    if($proveedor_form_factura_modal != "" && $servicio_form_factura_modal != "No filtrar"){
                        //proceso los de alante y los de atras
                        $resultado = $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->getParsedFiltradosPorNumeroAnnoProveedor(
                                $numero_form_factura_modal,
                                $anno_form_factura_modal,
                                $proveedor_form_factura_modal,
                                $servicio_form_factura_modal
                            );
                    }else{
                        //proceso los dos de alante y
                        $resultado = $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->getParsedFiltradosPorNumeroAnnoProveedor(
                                $numero_form_factura_modal,
                                $anno_form_factura_modal,
                                "",
                                ""
                            );
                    }
                }else{
                    if($proveedor_form_factura_modal != "" && $servicio_form_factura_modal != "No filtrar"){
                        //proceso los dos de atras
                        $resultado = $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->getParsedFiltradosPorNumeroAnnoProveedor(
                                0,
                                0,
                                $proveedor_form_factura_modal,
                                $servicio_form_factura_modal
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
            }
            else{
                //Ataque Csrf
                return new JsonResponse(array('data' => 'No es válida la forma en solicitar la respuesta del servidor','encontrado'=>'No'));
            }
        }else{
            //No es ajax
            return new JsonResponse(array('data' => 'No es válida la forma en solicitar la respuesta del servidor','encontrado'=>'No'));
        }
    }

    /**
     * @Route("/encontrar/proveedor/ajax", name="encontrar_proveedor_contrato_ajax", methods="POST")
     */
    public function encontrar_proveedor_contrato_ajax(Request $request): JsonResponse
    {
        $numero = $request->request->get('numero_contrato');
        $anno = $request->request->get('anno_contrato');
        $consulta = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getIdContratoYProveedorDadoNumeroYAnno($numero, $anno);

        if ($consulta!=null){
            return new JsonResponse(array('encontrado'=>'Si','id_contrato'=>$consulta[0]['id'],'proveedor'=>$consulta[0]['proveedor']));
        }else{
            return new JsonResponse(array('encontrado'=>'No','id_contrato'=>0,'proveedor'=>""));
        }
    }
}
