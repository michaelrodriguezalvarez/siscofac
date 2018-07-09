<?php

namespace App\Controller;

use App\Entity\Factura;
use App\Entity\Contrato;
use App\Entity\NomProveedor;
use App\Entity\NomProvincia;
use App\Entity\NomTipoServicio;
use App\Form\FacturaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Twig\ConfNotificacionExtension;

/**
 * @Route("/factura")
 */
class FacturaController extends Controller
{
    /**
     * @Route("/{id_contrato}/index", name="factura_index", methods="GET")
     */
    public function index(int $id_contrato): Response
    {
        $tipos_de_servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->getParsedFieldFromSelect();

        $tipos_de_servicios = array_flip($tipos_de_servicios);

        $facturas = $this->getDoctrine()
            ->getRepository(Factura::class)
            ->findBy(array('contrato'=>$id_contrato));
        $contrato = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->findOneBy(array('id'=>$id_contrato));
        $proveedor = $this->getDoctrine()
            ->getRepository(NomProveedor::class)
            ->find($contrato->getProveedor());

        $provincia = $this->getDoctrine()
            ->getRepository(NomProvincia::class)
            ->find($proveedor->getProvincia());

        $estados = array( 1 =>'Activo' , 0 => 'Inactivo');

        return $this->render('factura/index.html.twig', [
            'facturas' => $facturas,
            'id_contrato'=>$id_contrato,
            'contrato'=>$contrato,
            'proveedor'=>$proveedor,
            'provincia'=>$provincia,
            'estado'=>$estados[$contrato->getEstado()],
            'tipos_de_servicios'=>$tipos_de_servicios]);
    }

    /**
     * @Route("/{id_contrato}/new", name="factura_new", methods="GET|POST")
     */
    public function new(Request $request,int $id_contrato, RegistryInterface $doctrine): Response
    {
        $contrato = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->find($id_contrato);
        $estados = array( 1 =>'Activo' , 0 => 'Inactivo');

        $proveedor = $this->getDoctrine()
            ->getRepository(NomProveedor::class)
            ->find($contrato->getProveedor());

        $provincia = $this->getDoctrine()
            ->getRepository(NomProvincia::class)
            ->find($proveedor->getProvincia());
        $tipos_de_servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->getParsedFieldFromSelect();
        $estados_factura = array( 'Pagado' => 1 , 'No Pagado' => 0);
        $factura = new Factura();

        $form = $this->createForm(FacturaType::class, $factura,
            [
                'id_contrato'=>$id_contrato,
                'contrato_datos'=>$contrato,
                'proveedor'=>$proveedor.' - '.$provincia,
                'tipos_de_servicios'=>$tipos_de_servicios,
                'estados_factura'=>$estados_factura,
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           if ($request->request->get('factura')["valorCup"] > $contrato->getSaldoCup()){
               $this->addFlash(
                   'notice',
                   'No hay saldo en CUP suficiente en el contrato'
               );
           }
           else{
               if ($request->request->get('factura')["valorCuc"] > $contrato->getSaldoCuc()){
                   $this->addFlash(
                       'notice',
                       'No hay saldo en CUC suficiente en el contrato'
                   );
               }else{
                   $numero_factura = $request->request->get('factura')["numeroRegistro"];

                   $facturas_encontrados = $this->getDoctrine()
                       ->getRepository(Contrato::class)
                       ->getFacturaDadoNumeroYContrato($numero_factura,$id_contrato);
                   if (count($facturas_encontrados)==0){
                       $em = $this->getDoctrine()->getManager();
                       $em->persist($factura);

                           $this->getDoctrine()
                               ->getRepository(Contrato::class)
                               ->updateEjecucionContratoCUPYSaldoCUP($factura->getContrato(), $factura->getValorCup(),true);

                           $this->getDoctrine()
                               ->getRepository(Contrato::class)
                               ->updateEjecucionContratoCUCYSaldoCUC($factura->getContrato(), $factura->getValorCuc(),true);

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
                               ->updateEstado($id_contrato, $contrato->getMotivoEstado(), false);
                           $confNotificacionExtension = new ConfNotificacionExtension($doctrine);
                           $this->addFlash(
                               'notice',
                               $confNotificacionExtension->enviarCorreoNotificacion('saldo_insuficiente', $contrato)
                           );
                       }

                       $this->getDoctrine()
                           ->getRepository(Contrato::class)
                           ->chequearSaldo($id_contrato, $doctrine);

                       $this->addFlash(
                           'notice',
                           'Los datos fueron guardados satisfactoriamente'
                       );
                       return $this->redirectToRoute( 'factura_new',array('id_contrato'=>$id_contrato));
                   }
                   else{

                       $this->addFlash(
                           'notice',
                           'Debe especificar otro número para la factura'
                       );
                   }
               }
            }
        }

        return $this->render('factura/new.html.twig', [
            'estado'=> $estados[ $contrato->getEstado()],
            'id_contrato'=>$id_contrato,
            'contrato'=>$contrato,
            'factura' => $factura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_contrato}/{id}/show", name="factura_show", methods="GET")
     */
    public function show(Factura $factura, int $id_contrato): Response
    {
        $tipos_de_servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->getParsedFieldFromSelect();

        $tipos_de_servicios = array_flip($tipos_de_servicios);

        $facturas = $this->getDoctrine()
            ->getRepository(Factura::class)
            ->findBy(array('contrato'=>$id_contrato));
        $contrato = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->findOneBy(array('id'=>$id_contrato));
        $proveedor = $this->getDoctrine()
            ->getRepository(NomProveedor::class)
            ->find($contrato->getProveedor());

        $provincia = $this->getDoctrine()
            ->getRepository(NomProvincia::class)
            ->find($proveedor->getProvincia());

        $estados = array( 1 =>'Activo' , 0 => 'Inactivo');

        return $this->render('factura/show.html.twig',
            ['factura' => $factura,
                'id_contrato'=>$id_contrato,
                'contrato'=>$contrato,
                'proveedor'=>$proveedor,
                'provincia'=>$provincia,
                'estado'=>$estados[$contrato->getEstado()],
                'tipos_de_servicios'=>$tipos_de_servicios
            ]);
    }

    /**
     * @Route("/{id_contrato}/{id}/edit", name="factura_edit", methods="GET|POST")
     */
    public function edit(Request $request, Factura $factura, int $id_contrato, RegistryInterface $doctrine): Response
    {
        $contrato = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->find($id_contrato);
        $estados = array( 1 =>'Activo' , 0 => 'Inactivo');

        $proveedor = $this->getDoctrine()
            ->getRepository(NomProveedor::class)
            ->find($contrato->getProveedor());

        $provincia = $this->getDoctrine()
            ->getRepository(NomProvincia::class)
            ->find($proveedor->getProvincia());
        $tipos_de_servicios = $this->getDoctrine()
            ->getRepository(NomTipoServicio::class)
            ->getParsedFieldFromSelect();
        $estados_factura = array( 'Pagado' => 1 , 'No Pagado' => 0);

        $form = $this->createForm(FacturaType::class, $factura, [
            'id_contrato'=>$id_contrato,
            'contrato_datos'=>$contrato,
            'proveedor'=>$proveedor.' - '.$provincia,
            'tipos_de_servicios'=>$tipos_de_servicios,
            'estados_factura'=>$estados_factura,
            //'pagado_anteriormente' => $factura->getEstado(),
            'valor_anterior_cup'=>$factura->getValorCup(),
            'valor_anterior_cuc'=>$factura->getValorCuc(),
            ]);

        $valor_anterior_factura_cup = $factura->getValorCup();
        $valor_anterior_factura_cuc = $factura->getValorCuc();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$pagado_anteriormente = $request->request->get('factura')["pagado_anteriormente"];
            $saldo_sin_ejecucion_cup = $contrato->getSaldoCup();
            $saldo_sin_ejecucion_cuc = $contrato->getSaldoCuc();

            //if($pagado_anteriormente == 1){
            $saldo_sin_ejecucion_cup += $valor_anterior_factura_cup;
            $saldo_sin_ejecucion_cuc += $valor_anterior_factura_cuc;

            //}

            if ($request->request->get('factura')["valorCup"] > $saldo_sin_ejecucion_cup){
                $this->addFlash(
                    'notice',
                    'No hay saldo en CUP suficiente en el contrato'
                );
            }
            else{
                if ($request->request->get('factura')["valorCuc"] > $saldo_sin_ejecucion_cuc){
                    $this->addFlash(
                        'notice',
                        'No hay saldo en CUC suficiente en el contrato'
                    );
                }else {
                    //$pagado = $request->request->get('factura')["estado"];

                    //if ($pagado_anteriormente == 1) {
                        $this->getDoctrine()->getRepository(Contrato::class)
                            ->updateEjecucionContratoCUPYSaldoCUP($id_contrato, $valor_anterior_factura_cup, false);
                        $this->getDoctrine()->getRepository(Contrato::class)
                            ->updateEjecucionContratoCUCYSaldoCUC($id_contrato, $valor_anterior_factura_cuc, false);
                    //}

                    //if ($pagado == 1) {
                        $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->updateEjecucionContratoCUPYSaldoCUP($factura->getContrato(), $factura->getValorCup(), true);

                        $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->updateEjecucionContratoCUCYSaldoCUC($factura->getContrato(), $factura->getValorCuc(), true);
                    //}

                    $this->getDoctrine()->getManager()->flush();

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
                            ->updateEstado($id_contrato, $contrato->getMotivoEstado(), false);
                        $confNotificacionExtension = new ConfNotificacionExtension($doctrine);
                        $this->addFlash(
                            'notice',
                            $confNotificacionExtension->enviarCorreoNotificacion('saldo_insuficiente', $contrato)
                        );
                    }else{
                        $this->getDoctrine()
                            ->getRepository(Contrato::class)
                            ->updateEstado($id_contrato, "", true);
                    }

                    $this->getDoctrine()
                        ->getRepository(Contrato::class)
                        ->chequearSaldo($id_contrato, $doctrine);

            $this->addFlash(
                'notice',
                'Los datos fueron guardados satisfactoriamente'
            );

            return $this->redirectToRoute('factura_edit', ['id_contrato'=>$id_contrato, 'id' => $factura->getId()]);
                }

            }
        }

        return $this->render('factura/edit.html.twig', [
            'estado'=> $estados[ $contrato->getEstado()],
            'id_contrato'=>$id_contrato,
            'contrato'=>$contrato,
            'factura' => $factura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_contrato}/{id}", name="factura_delete", methods="DELETE")
     */
    public function delete(Request $request, Factura $factura, int $id_contrato, RegistryInterface $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factura->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
                //if($factura->getEstado()==1){
                    $this->getDoctrine()
                        ->getRepository(Contrato::class)
                        ->updateEjecucionContratoCUPYSaldoCUP($factura->getContrato(), $factura->getValorCup(),false);

                    $this->getDoctrine()
                        ->getRepository(Contrato::class)
                        ->updateEjecucionContratoCUCYSaldoCUC($factura->getContrato(), $factura->getValorCuc(),false);
                //    }
            $em->remove($factura);
            $em->flush();

            $contrato = $this->getDoctrine()
                ->getRepository(Contrato::class)
                ->find($id_contrato);
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
                    ->updateEstado($id_contrato, $contrato->getMotivoEstado(), false);
                $confNotificacionExtension = new ConfNotificacionExtension($doctrine);
                $this->addFlash(
                    'notice',
                    $confNotificacionExtension->enviarCorreoNotificacion('saldo_insuficiente', $contrato)
                );
            }else{
                $this->getDoctrine()
                    ->getRepository(Contrato::class)
                    ->updateEstado($id_contrato, "", true);
            }

            $this->getDoctrine()
                ->getRepository(Contrato::class)
                ->chequearSaldo($id_contrato, $doctrine);
        }

        return $this->redirectToRoute('factura_index',array('id_contrato'=>$id_contrato));
    }

    /**
     * @Route("/{id_contrato}/buscar/modal", name="factura_buscar_modal", methods="GET|POST")
     */
    public function factura_buscar_modal(Request $request,int $id_contrato): Response
    {
        return $this->render('factura/buscar.html.twig', [
            'id_contrato'=>$id_contrato,
        ]);
    }

    /**
     * @Route("/buscar/modal/ajax", name="factura_buscar_modal_ajax", methods="POST")
     */
    public function factura_buscar_modal_ajax(Request $request): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $token = $request->request->get('_token');
            if ($this->isCsrfTokenValid('factura_buscar_modal', $request->request->get('_token'))) {
                $numero_form_factura_buscar_modal = $request->request->get('numero_form_factura_buscar_modal');
                $id_contrato = $request->request->get('id_contrato_factura_buscar_modal');
                $resultado = $this->getDoctrine()->getRepository(Factura::class)->findOneBy(array('numeroRegistro'=>$numero_form_factura_buscar_modal,'contrato'=>$id_contrato));

                if ($resultado!=null){
                    return new JsonResponse(array('data' => $resultado->getId(),'encontrado'=>'Si'));
                }else{
                    return new JsonResponse(array('data' => 'No ha sido encontrado el factura','encontrado'=>'No'));
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
     * @Route("/{id_contrato}/modificar/modal", name="factura_modificar_modal", methods="GET|POST")
     */
    public function factura_modificar_modal(Request $request,int $id_contrato): Response
    {
        return $this->render('factura/modificar.html.twig', [
            'id_contrato'=>$id_contrato,
        ]);
    }

    /**
     * @Route("/modificar/modal/ajax", name="factura_modificar_modal_ajax", methods="POST")
     */
    public function factura_modificar_modal_ajax(Request $request): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $token = $request->request->get('_token');
            if ($this->isCsrfTokenValid('factura_modificar_modal', $request->request->get('_token'))) {
                $numero_form_factura_modificar_modal = $request->request->get('numero_form_factura_modificar_modal');
                $id_contrato = $request->request->get('id_contrato_factura_modificar_modal');
                $resultado = $this->getDoctrine()->getRepository(Factura::class)->findOneBy(array('numeroRegistro'=>$numero_form_factura_modificar_modal,'contrato'=>$id_contrato));

                if ($resultado!=null){
                    return new JsonResponse(array('data' => $resultado->getId(),'encontrado'=>'Si'));
                }else{
                    return new JsonResponse(array('data' => 'No ha sido encontrado el factura','encontrado'=>'No'));
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
}
