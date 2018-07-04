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
}