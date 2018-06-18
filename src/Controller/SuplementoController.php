<?php

namespace App\Controller;

use App\Entity\Suplemento;
use App\Entity\Contrato;
use App\Form\SuplementoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request,int $id_contrato): Response
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
                    ->updateValorTotalCUPYSaldoCUP($suplemento->getContrato(), $suplemento->getValorSuplementoCup(),true);
                $this->getDoctrine()
                    ->getRepository(Contrato::class)
                    ->updateValorTotalCUCYSaldoCUC($suplemento->getContrato(), $suplemento->getValorSuplementoCuc(),true);

                $em->flush();
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
    public function edit(Request $request, Suplemento $suplemento,int $id_contrato): Response
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
                ->updateValorTotalCUPYSaldoCUP($id_contrato, $valor_anterior_suplemento_cup, false);
            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUCYSaldoCUC($id_contrato, $valor_anterior_suplemento_cuc, false);
            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUPYSaldoCUP($id_contrato, $suplemento->getValorSuplementoCup(), true);
            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUCYSaldoCUC($id_contrato, $suplemento->getValorSuplementoCuc(), true);

            $this->getDoctrine()->getManager()->flush();

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
    public function delete(Request $request, Suplemento $suplemento,$id_contrato): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suplemento->getId(), $request->request->get('_token'))) {
            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUPYSaldoCUP($id_contrato, $suplemento->getValorSuplementoCup(), false);
            $this->getDoctrine()->getRepository(Contrato::class)
                ->updateValorTotalCUCYSaldoCUC($id_contrato, $suplemento->getValorSuplementoCuc(), false);
            $em = $this->getDoctrine()->getManager();
            $em->remove($suplemento);
            $em->flush();
        }

        return $this->redirectToRoute('suplemento_index',array('id_contrato'=>$id_contrato));
    }
}
