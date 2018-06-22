<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class FacturaRepository extends EntityRepository
{
    public function getSumatoriaSaldoCup(int $id_contrato): float
    {
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(fac.valorCup) AS valorCup')
            ->from('App\Entity\Factura','fac')
            ->where($this->getEntityManager()->createQueryBuilder()->expr()->eq('fac.contrato',$id_contrato));
        return $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult()[0]["valorCup"];
    }
    public function getSumatoriaSaldoCuc(int $id_contrato): float
    {
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(fac.valorCuc) AS valorCuc')
            ->from('App\Entity\Factura','fac')
            ->where($this->getEntityManager()->createQueryBuilder()->expr()->eq('fac.contrato',$id_contrato));
        return $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult()[0]["valorCuc"];
    }
}