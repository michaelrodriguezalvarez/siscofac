<?php

namespace App\Repository;

//use App\Entity\NomProveedor;
//use App\Entity\NomProvincia;
use Doctrine\ORM\EntityRepository;

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

}