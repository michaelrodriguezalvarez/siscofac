<?php

namespace App\Repository;

<<<<<<< .mine
use Doctrine\Common\Collections\ArrayCollection;

=======
//use App\Entity\NomProveedor;
//use App\Entity\NomProvincia;
>>>>>>> .theirs
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
<<<<<<< .mine
    public function getDatosParaListar():array {











































=======
    public function getDatosParaListar():array {
        $consulta_para_listar_contratos = $this->getEntityManager()->createQuery(
        '
        SELECT 
            con.id,
            con.numero,
            con.anno,
            con.fecha_inicio,
            con.fecha_terminacion,
            CONCAT(pro.nombre,\" - \",prv.nombre) AS proveedor,
            tds.nombre AS tipo_de_servicio,
            con.objeto,
            con.nit,
            con.reeup,
            con.carnet_identidad,
            tdp.nombre AS tipo_de_persona,
            con.cuenta_bancaria_cup,
            con.cuenta_bancaria_cuc,
            con.valor_contrato_inicial_cup,
            con.valor_contrato_inicial_cuc,
            con.valor_contrato_total_cup,
            con.valor_contrato_total_cuc,
            con.ejecucion_contrato_cup,
            con.ejecucion_contrato_cuc,
            con.saldo_cup,
            con.saldo_cuc,
            ban.nombre AS banco,
            CONCAT(\".No. \",acuc.numero,\" Fecha \",DATE_FORMAT(acuc.fecha,\"d/m/Y\")) AS aprob_contrato_comite_contratacion,
            CONCAT(\"No. \",acua.numero,\" Fecha \",DATE_FORMAT(acua.fecha,\"d/m/Y\")) AS aprob_contrato_comite_administracion,
            are.nombre AS area_administra_contrato,
            con.estado
            
         FROM App\Entity\Contrato con
         INNER JOIN App\Entity\NomProveedor pro ON con.proveedor = pro.id
         INNER JOIN App\Entity\NomProvincia prv ON pro.provincia = prv.id
         INNER JOIN App\Entity\NomTipoServicio tds ON con.tipo_de_servicio = tds.id
         INNER JOIN App\Entity\NomTipoPersona tdp ON con.tipo_de_persona = tdp.id
         INNER JOIN App\Entity\NomBanco ban ON con.banco = ban.id
         INNER JOIN App\Entity\Acuerdo acuc ON con.aprob_contrato_comite_contratacion = acuc.id
         INNER JOIN App\Entity\Acuerdo acua ON con.aprob_contrato_comite_administracion = acua.id
         INNER JOIN App\Entity\NomArea are ON con.area_administra_contrato = are.id
        ');
        return $consulta_para_listar_contratos->getArrayResult();
    }
>>>>>>> .theirs

<<<<<<< .mine
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

=======
    public function getNombreProvinciaProveedorPorId($id):string {
        $consulta_proveedor = $this->getEntityManager()->createQuery(
            'SELECT p.nombre,p.provincia FROM App\Entity\NomProveedor p
                  WHERE p.id = :id'
        )->setParameter('id',$id);

        $proveedor = $consulta_proveedor->getSingleResult();

        $consulta_provincia = $this->getEntityManager()->createQuery(
            'SELECT p.nombre FROM App\Entity\NomProvincia p
                  WHERE p.id = :id'
        )->setParameter('id',$proveedor['provincia']);

        $provincia = $consulta_provincia->getSingleResult();

        return $proveedor['nombre']." - ". $provincia['nombre'];
    }




























































>>>>>>> .theirs
}