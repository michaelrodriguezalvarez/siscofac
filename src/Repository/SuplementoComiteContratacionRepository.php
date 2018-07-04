<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\ORMInvalidArgumentException;

class SuplementoComiteContratacionRepository extends EntityRepository
{
    public function getDatosParaListar():array {

        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('scc.id')
            ->addSelect('CONCAT(con.numero, \' / \' ,con.anno) AS contrato')
            ->addSelect('scc.numero')
            ->addSelect('scc.objeto')
            ->addSelect('scc.valorCup')
            ->addSelect('scc.valorCuc')
            ->from('App\Entity\SuplementoComiteContratacion','scc')
            ->innerJoin('App\Entity\Contrato','con',Expr\Join::WITH,'con.id = scc.contrato')
        ;
        $contratos_para_listar = $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
        return $contratos_para_listar;
    }

    public function getParsedFiltradosPorNumeroContratoAnnoNumero(int $numero_contrato,int $anno, int $numero):array {
        if ($numero_contrato!="" && $anno !="" && $numero !=""){
            $consulta = $this->getEntityManager()->createQueryBuilder()
                ->select('scc.id')
                ->from('App\Entity\SuplementoComiteContratacion','scc')
                ->innerJoin('App\Entity\Contrato','con',Expr\Join::WITH,'con.id = scc.contrato')
            ;
            $consulta->where($consulta->expr()->eq('con.numero',$numero_contrato));
            $consulta->andWhere($consulta->expr()->eq('con.anno',$anno));
            $consulta->andWhere($consulta->expr()->eq('scc.numero',$numero));
            return $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
        }else{
            throw new ORMInvalidArgumentException("Debe especificar los parámetros correctos");
        }
    }
}