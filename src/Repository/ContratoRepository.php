<?php

namespace App\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;


class ContratoRepository extends EntityRepository
{
    public function getUltimosNAnnosHastaActual($n): array
    {
        $anno_actual = date('Y');
        $annos = array();

        while ($n >= 0) {
            array_unshift($annos, $anno_actual - $n);
            $n--;
        }

        return array_combine($annos, $annos);
    }
    public function getDatosParaListar():array {

        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('con.id')
            ->addSelect(' con.numero')
            ->addSelect('con.anno')
            ->addSelect('con.fechaInicio')
            ->addSelect('con.fechaTerminacion')
            ->addSelect('CONCAT(pro.nombre, \'-\' ,prv.nombre) AS proveedor')
            ->addSelect('tds.nombre AS tipoDeServicio')
            ->addSelect('con.objeto')
            ->addSelect('con.nit')
            ->addSelect('con.reeup')
            ->addSelect('con.carnetIdentidad')
            ->addSelect('tdp.nombre AS tipoDePersona')
            ->addSelect('con.cuentaBancariaCup')
            ->addSelect('con.cuentaBancariaCuc')
            ->addSelect('con.valorContratoInicialCup')
            ->addSelect('con.valorContratoInicialCuc')
            ->addSelect('con.valorContratoTotalCup')
            ->addSelect('con.valorContratoTotalCuc')
            ->addSelect('con.ejecucionContratoCup')
            ->addSelect('con.ejecucionContratoCuc')
            ->addSelect('con.saldoCup')
            ->addSelect('con.saldoCuc')
            ->addSelect('ban.nombre AS banco')
            ->addSelect('con.numeroAprobContratoComiteContratacion')
            ->addSelect('con.fechaAprobContratoComiteContratacion')
            ->addSelect('con.numeroAprobContratoComiteAdministracion')
            ->addSelect('con.fechaAprobContratoComiteAdministracion')
            ->addSelect('are.nombre AS areaAdministraContrato')
            ->addSelect('con.estado')
            ->from('App\Entity\Contrato','con')
            ->innerJoin('App\Entity\NomProveedor','pro',Expr\Join::WITH,'con.proveedor = pro.id')
            ->innerJoin('App\Entity\NomProvincia','prv', Expr\Join::WITH, 'pro.provincia = prv.id')
            ->innerJoin('App\Entity\NomTipoServicio','tds', Expr\Join::WITH, 'con.tipoDeServicio = tds.id')
            ->innerJoin('App\Entity\NomTipoPersona','tdp', Expr\Join::WITH, 'con.tipoDePersona = tdp.id')
            ->innerJoin('App\Entity\NomBanco','ban', Expr\Join::WITH, 'con.banco = ban.id')
            ->innerJoin('App\Entity\NomArea','are', Expr\Join::WITH, 'con.areaAdministraContrato = are.id')
        ;
        $contratos_para_listar = $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
        return $contratos_para_listar;
    }

    public function getParsedFieldFromSelect(): array
    {
        $contratos = $this->findAll();
        $resultado_nombres = array();
        $resultado_ids = array();

        foreach ($contratos as $contrato) {
            array_push($resultado_nombres, $contrato->getNumero());
            array_push($resultado_ids, $contrato->getId());
        }

        return array_combine($resultado_nombres,$resultado_ids);
    }

    public function updateValorTotalCUPYSaldoCUP(int $id_contrato, float $incremento):int{
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->update('App\Entity\Contrato','con')
            ->set('con.valorContratoTotalCup', 'con.valorContratoTotalCup + :incremento')
            ->set('con.saldoCup', 'con.saldoCup + :incremento')
            ->where('con.id = :id_contrato')
            ->setParameters(array('id_contrato'=>$id_contrato,'incremento'=>$incremento))
            ->getQuery();
        return $consulta->execute();
    }
    public function updateValorTotalCUCYSaldoCUC(int $id_contrato, float $incremento):int{
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->update('App\Entity\Contrato','con')
            ->set('con.valorContratoTotalCuc', 'con.valorContratoTotalCuc + :incremento')
            ->set('con.saldoCuc', 'con.saldoCuc + :incremento')
            ->where('con.id = :id_contrato')
            ->setParameters(array('id_contrato'=>$id_contrato,'incremento'=>$incremento))
            ->getQuery();
        return $consulta->execute();
    }

}