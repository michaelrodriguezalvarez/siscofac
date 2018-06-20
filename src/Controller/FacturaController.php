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

        return $this->render('factura/index.html.twig', ['facturas' => $facturas, 'id_contrato'=>$id_contrato,'contrato'=>$contrato,'proveedor'=>$proveedor,'provincia'=>$provincia,'estado'=>$estados[$contrato->getEstado()],'tipos_de_servicios'=>$tipos_de_servicios]);
    }

    /**
     * @Route("/{id_contrato}/new", name="factura_new", methods="GET|POST")
     */
    public function new(Request $request,int $id_contrato): Response
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
            $numero_factura = $request->request->get('factura')["numero_registro"];

            $facturas_encontrados = $this->getDoctrine()
                ->getRepository(Contrato::class)
                ->getFacturaDadoNumeroYContrato($numero_factura,$id_contrato);
            if (count($facturas_encontrados)==0){
                $em = $this->getDoctrine()->getManager();
                $em->persist($factura);
                $pagado = $request->request->get('factura')["estado"];
                if($pagado==1){
                    $this->getDoctrine()
                        ->getRepository(Contrato::class)
                        ->updateEjecucionContratoCUPYSaldoCUP($factura->getContrato(), $factura->getValorCup(),true);

                    $this->getDoctrine()
                        ->getRepository(Contrato::class)
                        ->updateEjecucionContratoCUCYSaldoCUC($factura->getContrato(), $factura->getValorCuc(),true);
                }



                $em->flush();
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

        return $this->render('factura/new.html.twig', [
            'estado'=> $estados[ $contrato->getEstado()],
            'id_contrato'=>$id_contrato,
            'contrato'=>$contrato,
            'factura' => $factura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factura_show", methods="GET")
     */
    public function show(Factura $factura): Response
    {
        return $this->render('factura/show.html.twig', ['factura' => $factura]);
    }

    /**
     * @Route("/{id}/edit", name="factura_edit", methods="GET|POST")
     */
    public function edit(Request $request, Factura $factura): Response
    {
        $form = $this->createForm(FacturaType::class, $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('factura_edit', ['id' => $factura->getId()]);
        }

        return $this->render('factura/edit.html.twig', [
            'factura' => $factura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factura_delete", methods="DELETE")
     */
    public function delete(Request $request, Factura $factura): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factura->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($factura);
            $em->flush();
        }

        return $this->redirectToRoute('factura_index');
    }
}
