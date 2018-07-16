<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class ContratoComiteContratacionRepository extends EntityRepository
{
    public function getMaximoValorOrden():int
    {
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('MAX(ccc.orden)')
            ->from('App\Entity\ContratoComiteContratacion','ccc');
        $resultado = $this->getEntityManager()->createQuery($consulta->getDQL())->getResult();

        if ($resultado[0][1] == null){
            return 1;
        }
        else{
            return $resultado[0][1]+1;
        }
    }

    public function getDatosParaListar():array {

        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('ccc.id')
            ->addSelect('ccc.orden')
            ->addSelect('CONCAT(pro.nombre, \'-\' ,prv.nombre) AS proveedor')
            ->addSelect('tds.nombre AS tipoDeServicio')
            ->addSelect('ccc.objeto')
            ->addSelect('ccc.valorContratoInicialCup')
            ->addSelect('ccc.valorContratoInicialCuc')
            ->addSelect('are.nombre AS areaAdministraContrato')
            ->addSelect('ccc.fechaDeReunion')
            ->from('App\Entity\ContratoComiteContratacion','ccc')
            ->innerJoin('App\Entity\NomProveedor','pro',Expr\Join::WITH,'ccc.proveedor = pro.id')
            ->innerJoin('App\Entity\NomProvincia','prv', Expr\Join::WITH, 'pro.provincia = prv.id')
            ->innerJoin('App\Entity\NomTipoServicio','tds', Expr\Join::WITH, 'ccc.tipoDeServicio = tds.id')
            ->innerJoin('App\Entity\NomArea','are', Expr\Join::WITH, 'ccc.areaAdministraContrato = are.id')
        ;
        $contratos_para_listar = $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
        return $contratos_para_listar;
    }

    public function getParsedFiltradosPorNumeroProveedor(int $numero,string $proveedor):array {
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('ccc.id')
            ->addSelect('ccc.orden')
            ->addSelect('CONCAT(pro.nombre, \'-\' ,prv.nombre) AS proveedor')
            ->addSelect('tds.nombre AS tipoDeServicio')
            ->addSelect('ccc.objeto')
            ->addSelect('ccc.valorContratoInicialCup')
            ->addSelect('ccc.valorContratoInicialCuc')
            ->addSelect('are.nombre AS areaAdministraContrato')
            ->addSelect('ccc.fechaDeReunion')
            ->from('App\Entity\ContratoComiteContratacion','ccc')
            ->innerJoin('App\Entity\NomProveedor','pro',Expr\Join::WITH,'ccc.proveedor = pro.id')
            ->innerJoin('App\Entity\NomProvincia','prv', Expr\Join::WITH, 'pro.provincia = prv.id')
            ->innerJoin('App\Entity\NomTipoServicio','tds', Expr\Join::WITH, 'ccc.tipoDeServicio = tds.id')
            ->innerJoin('App\Entity\NomArea','are', Expr\Join::WITH, 'ccc.areaAdministraContrato = are.id')
        ;

        if ($numero != 0){
            $consulta->where($consulta->expr()->eq('ccc.orden',$numero));
            if ($proveedor != ""){
                $consulta->andWhere('pro.nombre LIKE \'%'.$proveedor.'%\' OR prv.nombre LIKE \'%'.$proveedor.'%\'');
            }
        }else{
            if ($proveedor != ""){
                $consulta->where('pro.nombre LIKE \'%'.$proveedor.'%\' OR prv.nombre LIKE \'%'.$proveedor.'%\'');
            }else{
                throw new ORMInvalidArgumentException("Debe especificar los parámetros correctos");
            }
        }
        return $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
    }

    public function getContenidoParaExportarExcel():string
    {
        $contratos_comite_contratacion = $this->getDatosParaListar();
        $contenido = '<table width="50%" border="1" cellpadding="10" cellspacing="0" bordercolor="#666666" style="border-collapse:collapse;">';
        $contenido .= '<thead>';
        $contenido .= '<tr>';
        $contenido .= '<th>Orden</th>';
        $contenido .= '<th>Proveedor</th>';
        $contenido .= '<th>Tipo de servicio</th>';
        $contenido .= '<th>Objeto</th>';
        $contenido .= '<th>Valor contrato inicial cup CUP</th>';
        $contenido .= '<th>Valor contrato inicial cuc CUC</th>';
        $contenido .= '<th>Area administra contrato</th>';
        $contenido .= '<th>Fecha de reunion</th>';
        $contenido .= '</tr>';
        $contenido .= '</thead>';
        $contenido .= '<tbody>';
        foreach ($contratos_comite_contratacion as $contrato_comite_contratacion){
            $contenido .= '
                    <tr>
                         <td> '.$contrato_comite_contratacion["orden"].' </td>
                         <td> '.$contrato_comite_contratacion["proveedor"].' </td>
                         <td> '.$contrato_comite_contratacion["tipoDeServicio"].' </td>
                         <td> '.$contrato_comite_contratacion["objeto"].' </td>
                         <td> '.$contrato_comite_contratacion["valorContratoInicialCup"].' </td>
                         <td> '.$contrato_comite_contratacion["valorContratoInicialCuc"].' </td>
                         <td> '.$contrato_comite_contratacion["areaAdministraContrato"].' </td>
                         <td> '.$contrato_comite_contratacion["fechaDeReunion"]->format("d-m-Y").'</td>
                     </tr>                           
            ';
        }
        $contenido .= '</tbody>';
        $contenido .= '</table>';
        return $contenido;
    }
}