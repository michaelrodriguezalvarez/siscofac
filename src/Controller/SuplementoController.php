<?php

namespace App\Controller;

use App\Entity\Suplemento;
use App\Entity\Contrato;
use App\Entity\Factura;
use App\Form\SuplementoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Twig\ConfNotificacionExtension;

/**
 * @Route("/suplemento")
 */
class SuplementoController extends Controller
{
    /**
     * @Route("/{id_contrato}/index", name="suplemento_index", methods="GET")
     */
    public function index(int $id_contrato): Response
    {
        $suplementos = $this->getDoctrine()
            ->getRepository(Suplemento::class)
            ->findBy(array('contrato'=>$id_contrato));
        $contrato = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->findOneBy(array('id'=>$id_contrato));


        return $this->render('suplemento/index.html.twig', ['suplementos' => $suplementos,'id_contrato'=>$id_contrato,'contrato'=>$contrato]);
    }

    /**
     * @Route("/{id_contrato}/new", name="suplemento_new", methods="GET|POST")
     */
    public function new(Request $request,int $id_contrato, RegistryInterface $doctrine): Response
    {
        $contrato = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->find($id_contrato);

        $suplemento = new Suplemento();
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

                $this->addFlash(
                    'notice',
                    'Los datos fueron guardados satisfactoriamente'
                );
                return $this->redirectToRoute( 'suplemento_new',array('id_contrato'=>$id_contrato));
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
     * @Route("/{id_contrato}/{id}/show", name="suplemento_show", methods="GET")
     */
    public function show(Suplemento $suplemento,int $id_contrato): Response
    {
        $contrato = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->find($id_contrato);

        return $this->render('suplemento/show.html.twig',
            ['suplemento' => $suplemento,
            'id_contrato'=>$id_contrato,
            'contrato'=>$contrato,
            ]);
    }

    /**
     * @Route("/{id_contrato}/{id}/edit", name="suplemento_edit", methods="GET|POST")
     */
    public function edit(Request $request, Suplemento $suplemento,int $id_contrato, RegistryInterface $doctrine): Response
    {
        $contrato = $this->getDoctrine()
            ->getRepository(Contrato::class)
            ->find($id_contrato);

        $form = $this->createForm(SuplementoType::class, $suplemento,['id_contrato'=>$id_contrato]);
        $valor_anterior_suplemento_cup = $suplemento->getValorSuplementoCup();
        $valor_anterior_suplemento_cuc = $suplemento->getValorSuplementoCuc();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUPYSaldoCUP($contrato, $valor_anterior_suplemento_cup, false);
            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUCYSaldoCUC($contrato, $valor_anterior_suplemento_cuc, false);
            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUPYSaldoCUP($contrato, $suplemento->getValorSuplementoCup(), true);
            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUCYSaldoCUC($contrato, $suplemento->getValorSuplementoCuc(), true);

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

            $this->addFlash(
                'notice',
                'Los datos fueron guardados satisfactoriamente'
            );

            return $this->redirectToRoute('suplemento_edit', ['id_contrato'=>$id_contrato, 'id' => $suplemento->getId()]);
        }

        return $this->render('suplemento/edit.html.twig', [
            'id_contrato'=>$id_contrato,
            'contrato'=>$contrato,
            'suplemento' => $suplemento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_contrato}/{id}", name="suplemento_delete", methods="DELETE")
     */
    public function delete(Request $request, Suplemento $suplemento, int $id_contrato, RegistryInterface $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suplemento->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $contrato = $em->getRepository(Contrato::class)->find($id_contrato);
            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUPYSaldoCUP($contrato, $suplemento->getValorSuplementoCup(), false);
            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUCYSaldoCUC($contrato, $suplemento->getValorSuplementoCuc(), false);

            $em->remove($suplemento);
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
        }

        return $this->redirectToRoute('suplemento_index',array('id_contrato'=>$id_contrato));
    }

    /**
     * @Route("/{id_contrato}/buscar/modal", name="suplemento_buscar_modal", methods="GET|POST")
     */
    public function suplemento_buscar_modal(Request $request,int $id_contrato): Response
    {
        return $this->render('suplemento/buscar.html.twig', [
            'id_contrato'=>$id_contrato,
        ]);
    }

    /**
     * @Route("/buscar/modal/ajax", name="suplemento_buscar_modal_ajax", methods="POST")
     */
    public function suplemento_buscar_modal_ajax(Request $request): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $token = $request->request->get('_token');
            if ($this->isCsrfTokenValid('suplemento_buscar_modal', $request->request->get('_token'))) {
                $numero_form_suplemento_buscar_modal = $request->request->get('numero_form_suplemento_buscar_modal');
                $id_contrato = $request->request->get('id_contrato_suplemento_buscar_modal');
                $resultado = $this->getDoctrine()->getRepository(Suplemento::class)->findOneBy(array('numero'=>$numero_form_suplemento_buscar_modal,'contrato'=>$id_contrato));

                if ($resultado!=null){
                    return new JsonResponse(array('data' => $resultado->getId(),'encontrado'=>'Si'));
                }else{
                    return new JsonResponse(array('data' => 'No ha sido encontrado el suplemento','encontrado'=>'No'));
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
     * @Route("/{id_contrato}/modificar/modal", name="suplemento_modificar_modal", methods="GET|POST")
     */
    public function suplemento_modificar_modal(Request $request,int $id_contrato): Response
    {
        return $this->render('suplemento/modificar.html.twig', [
            'id_contrato'=>$id_contrato,
        ]);
    }

    /**
     * @Route("/modificar/modal/ajax", name="suplemento_modificar_modal_ajax", methods="POST")
     */
    public function suplemento_modificar_modal_ajax(Request $request): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $token = $request->request->get('_token');
            if ($this->isCsrfTokenValid('suplemento_modificar_modal', $request->request->get('_token'))) {
                $numero_form_suplemento_modificar_modal = $request->request->get('numero_form_suplemento_modificar_modal');
                $id_contrato = $request->request->get('id_contrato_suplemento_modificar_modal');
                $resultado = $this->getDoctrine()->getRepository(Suplemento::class)->findOneBy(array('numero'=>$numero_form_suplemento_modificar_modal,'contrato'=>$id_contrato));

                if ($resultado!=null){
                    return new JsonResponse(array('data' => $resultado->getId(),'encontrado'=>'Si'));
                }else{
                    return new JsonResponse(array('data' => 'No ha sido encontrado el suplemento','encontrado'=>'No'));
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
