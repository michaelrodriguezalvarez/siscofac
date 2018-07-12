<?php

namespace App\Controller;

use App\Entity\Contrato;
use App\Entity\Suplemento;
use App\Entity\Factura;
use App\Entity\SuplementoComiteContratacion;
use App\Form\SuplementoType;
use App\Form\SuplementoComiteContratacionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Twig\ConfNotificacionExtension;

/**
 * @Route("/suplemento/comite/contratacion")
 */
class SuplementoComiteContratacionController extends Controller
{
    /**
     * @Route("/", name="suplemento_comite_contratacion_index", methods="GET")
     */
    public function index(): Response
    {
        $suplementoComiteContratacions = $this->getDoctrine()
            ->getRepository(SuplementoComiteContratacion::class)
            ->getDatosParaListar();

        return $this->render('suplemento_comite_contratacion/index.html.twig', ['suplemento_comite_contratacions' => $suplementoComiteContratacions]);
    }

    /**
     * @Route("/new", name="suplemento_comite_contratacion_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $ultimos_annos_hasta_actual = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getUltimosNAnnosHastaActual(10);
        $ruta = $this->generateUrl('encontrar_proveedor_contrato_ajax');

        $suplementoComiteContratacion = new SuplementoComiteContratacion();
        $form = $this->createForm(SuplementoComiteContratacionType::class, $suplementoComiteContratacion, [
            'ruta'=>$ruta,
            'ultimos_annos_hasta_actual'=>$ultimos_annos_hasta_actual,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($suplementoComiteContratacion);
            $em->flush();

            $this->addFlash(
                'notice',
                'Los datos fueron guardados satisfactoriamente'
            );

            return $this->redirectToRoute('suplemento_comite_contratacion_new');
        }

        return $this->render('suplemento_comite_contratacion/new.html.twig', [
            'suplemento_comite_contratacion' => $suplementoComiteContratacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="suplemento_comite_contratacion_show", methods="GET")
     */
    public function show(SuplementoComiteContratacion $suplementoComiteContratacion): Response
    {
        return $this->render('suplemento_comite_contratacion/show.html.twig', ['suplemento_comite_contratacion' => $suplementoComiteContratacion]);
    }

    /**
     * @Route("/{id}/edit", name="suplemento_comite_contratacion_edit", methods="GET|POST")
     */
    public function edit(Request $request, SuplementoComiteContratacion $suplementoComiteContratacion): Response
    {
        $ultimos_annos_hasta_actual = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getUltimosNAnnosHastaActual(10);
        $contrato = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getNumeroAnnoProveedorContratoDadoIdContrato($suplementoComiteContratacion->getContrato());

        $form = $this->createForm(SuplementoComiteContratacionType::class, $suplementoComiteContratacion,[
        //    'ruta'=>$ruta,
            'ultimos_annos_hasta_actual'=>$ultimos_annos_hasta_actual,
        ]);

        $form->add('contrato_numero',TextType::class,array(
            'label'=>'No. Contrato',
            'data'=>$contrato[0]['numero'],
            'mapped'=>false,
            'disabled'=>true
        ))
        ->add('contrato_anno',TextType::class, array(
            'label'=>'Año de Contrato',
            'data'=>$contrato[0]['anno'],
            'mapped'=>false,
            'disabled'=>true
        ))
        ->add('contrato_proveedor',TextType::class, array(
            'label'=>'Proveedor',
            'data'=>$contrato[0]['proveedor'],
            'mapped'=>false,
            'disabled'=>true
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'notice',
                'Los datos fueron guardados satisfactoriamente'
            );
            return $this->redirectToRoute('suplemento_comite_contratacion_edit', ['id' => $suplementoComiteContratacion->getId()]);
        }

        return $this->render('suplemento_comite_contratacion/edit.html.twig', [
            'suplemento_comite_contratacion' => $suplementoComiteContratacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="suplemento_comite_contratacion_delete", methods="DELETE")
     */
    public function delete(Request $request, SuplementoComiteContratacion $suplementoComiteContratacion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suplementoComiteContratacion->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($suplementoComiteContratacion);
            $em->flush();
        }

        return $this->redirectToRoute('suplemento_comite_contratacion_index');
    }

    /**
     * @Route("/modificar/modal", name="suplemento_comite_contratacion_modificar_modal", methods="GET|POST")
     */
    public function suplemento_comite_contratacion_modificar_modal(Request $request): Response
    {
        $ultimos_annos_hasta_actual = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->getUltimosNAnnosHastaActual(10);
        return $this->render('suplemento_comite_contratacion/modificar.html.twig',array(
            'ultimos_annos_hasta_actual'=>$ultimos_annos_hasta_actual
        ));
    }
    /**
     * @Route("/modificar/modal/ajax", name="suplemento_comite_contratacion_modificar_modal_ajax", methods="POST")
     */
    public function suplemento_modificar_modal_ajax(Request $request): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $token = $request->request->get('_token');
            if ($this->isCsrfTokenValid('suplemento_modificar_modal', $request->request->get('_token'))) {

                $numero_contrato_form_suplemento_modificar_modal =  $request->request->get('numero_contrato_form_suplemento_modificar_modal');
                $anno_form_contrato_buscar_modal = $request->request->get('anno_form_contrato_buscar_modal');
                $numero_form_suplemento_modificar_modal = $request->request->get('numero_form_suplemento_modificar_modal');

                $resultado = array();

                if($numero_contrato_form_suplemento_modificar_modal != "" && $anno_form_contrato_buscar_modal != null && $numero_form_suplemento_modificar_modal !=""){
                        $resultado = $this->getDoctrine()
                            ->getRepository(SuplementoComiteContratacion::class)
                            ->getParsedFiltradosPorNumeroContratoAnnoNumero($numero_contrato_form_suplemento_modificar_modal,$anno_form_contrato_buscar_modal, $numero_form_suplemento_modificar_modal);
                }else{
                    //error debe llenar campos
                    return new JsonResponse(array('data' => 'Es requerido completar los campos','encontrado'=>'No'));
                }

                try{
                    if(count($resultado)==1){
                        return new JsonResponse(array('data' => $resultado[0]['id'],'encontrado'=>'Si'));
                    }else{
                        return new JsonResponse(array('data' => 'No ha sido encontrado el suplemento','encontrado'=>'No'));
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
     * @Route("/aprobar/{id}", name="suplemento_comite_contratacion_aprobar", methods="GET|POST")
     */
    public function aprobar(int $id, Request $request, RegistryInterface $doctrine):Response{
        $suplementoComiteContratacion = $this->getDoctrine()
            ->getRepository(SuplementoComiteContratacion::class)
            ->find($id);

        $contrato = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->find($suplementoComiteContratacion->getContrato());

        $suplemento = new Suplemento();

        $suplemento->setNumero($suplementoComiteContratacion->getNumero());
        $suplemento->setObjeto($suplementoComiteContratacion->getObjeto());
        $suplemento->setValorSuplementoCup($suplementoComiteContratacion->getValorCup());
        $suplemento->setValorSuplementoCuc($suplementoComiteContratacion->getValorCuc());
        $suplemento->setContrato($suplementoComiteContratacion->getContrato());

        $id_contrato = $contrato->getId();


        $form = $this->createForm(SuplementoType::class, $suplemento,['id_contrato'=>$id_contrato]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $numero_suplemento = $request->request->get('suplemento')["numero"];
            $suplementos_encontrados = $this->getDoctrine()
                ->getRepository(Contrato::class)
                ->getSuplementoDadoNumeroYContrato($numero_suplemento,$id_contrato);
            if (count($suplementos_encontrados)==0){
                $em = $this->getDoctrine()->getManager();
                $em->persist($suplemento);
                $this->getDoctrine()
                    ->getRepository(Contrato::class)
                    ->updateValorTotalCUPYSaldoCUP($contrato, $suplemento->getValorSuplementoCup(),true);
                $this->getDoctrine()
                    ->getRepository(Contrato::class)
                    ->updateValorTotalCUCYSaldoCUC($contrato, $suplemento->getValorSuplementoCuc(),true);

                $em->flush();

                $valor_ejecutado_facturas_cup = $this->getDoctrine()
                    ->getRepository(Factura::class)
                    ->getSumatoriaSaldoCup($id_contrato);
                $valor_ejecutado_facturas_cuc = $this->getDoctrine()
                    ->getRepository(Factura::class)
                    ->getSumatoriaSaldoCuc($id_contrato);

                if ($valor_ejecutado_facturas_cup == $contrato->getValorContratoTotalCup() && $valor_ejecutado_facturas_cuc == $contrato->getValorContratoTotalCuc()){
                    $contrato->setMotivoEstado("Por poseer un saldo insuficiente");
                    $this->getDoctrine()
                        ->getRepository(Contrato::class)
                        ->updateEstado($id_contrato, false);

                        $confNotificacionExtension = new ConfNotificacionExtension($doctrine);
                        $this->addFlash(
                        'notice',
                        $confNotificacionExtension->enviarCorreoNotificacion('saldo_insuficiente', $contrato)
                    );
                }else{
                    $this->getDoctrine()
                        ->getRepository(Contrato::class)
                        ->updateEstado($id_contrato, true);
                }

                $em = $this->getDoctrine()->getManager();
                $em->remove($suplementoComiteContratacion);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Los datos fueron guardados satisfactoriamente'
                );
                return $this->redirectToRoute( 'suplemento_comite_contratacion_index');
            }
            else{

                $this->addFlash(
                    'notice',
                    'Debe especificar otro número para el suplemento'
                );
            }
        }

        return $this->render('suplemento/new.html.twig', [
            'id_contrato'=>$id_contrato,
            'contrato'=>$contrato,
            'suplemento' => $suplemento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/denegar/{id}", name="suplemento_comite_contratacion_denegar", methods="GET|POST")
     */
    public function denegar($id):Response{
        $suplementoComiteContratacion = $this->getDoctrine()->getRepository(SuplementoComiteContratacion::class)->find($id);
        if ($suplementoComiteContratacion != null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($suplementoComiteContratacion);
            $em->flush();
            $this->addFlash(
                'notice',
                'Suplemento denegado satisfactoriamente'
            );
            return $this->redirectToRoute('suplemento_comite_contratacion_index');
        }else{
            $this->addFlash(
                'notice',
                'Error no se pudo encontrar el registro'
            );
        }

    }
}
