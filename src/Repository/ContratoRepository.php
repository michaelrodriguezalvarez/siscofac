<?php

namespace App\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Query\Expr;
use App\Twig\ConfNotificacionExtension;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Contrato;

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
            ->addSelect('con.motivoEstado')
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
            array_push($resultado_nombres, $contrato->getNumero().'/'.$contrato->getAnno());
            array_push($resultado_ids, $contrato->getId());
        }

        return array_combine($resultado_nombres,$resultado_ids);
    }

    public function updateValorTotalCUPYSaldoCUP(int $id_contrato, float $monto, bool $incremento):int{
        $signo = $incremento == true ? '+' : '-';
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->update('App\Entity\Contrato','con')
            ->set('con.valorContratoTotalCup', 'con.valorContratoTotalCup '.$signo.' :monto')
            ->set('con.saldoCup', 'con.saldoCup '.$signo.' :monto')
            ->where('con.id = :id_contrato')
            ->setParameters(array('id_contrato'=>$id_contrato,'monto'=>$monto))
            ->getQuery();
        return $consulta->execute();
    }
    public function updateValorTotalCUCYSaldoCUC(int $id_contrato, float $monto, bool $incremento):int{
        $signo = $incremento == true ? '+' : '-';
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->update('App\Entity\Contrato','con')
            ->set('con.valorContratoTotalCuc', 'con.valorContratoTotalCuc '.$signo.' :monto')
            ->set('con.saldoCuc', 'con.saldoCuc '.$signo.' :monto')
            ->where('con.id = :id_contrato')
            ->setParameters(array('id_contrato'=>$id_contrato,'monto'=>$monto))
            ->getQuery();
        return $consulta->execute();;
    }
    public function getParsedFiltradosPorNumeroAnnoProveedor(int $numero,int $anno,string $proveedor,string $tipo_de_servicio):array {

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
                ->addSelect('con.motivoEstado')
                ->from('App\Entity\Contrato','con')
                ->innerJoin('App\Entity\NomProveedor','pro',Expr\Join::WITH,'con.proveedor = pro.id')
                ->innerJoin('App\Entity\NomProvincia','prv', Expr\Join::WITH, 'pro.provincia = prv.id')
                ->innerJoin('App\Entity\NomTipoServicio','tds', Expr\Join::WITH, 'con.tipoDeServicio = tds.id')
                ->innerJoin('App\Entity\NomTipoPersona','tdp', Expr\Join::WITH, 'con.tipoDePersona = tdp.id')
                ->innerJoin('App\Entity\NomBanco','ban', Expr\Join::WITH, 'con.banco = ban.id')
                ->innerJoin('App\Entity\NomArea','are', Expr\Join::WITH, 'con.areaAdministraContrato = are.id')
            ;

            if ($numero != 0 && $anno != 0){
                $consulta->where($consulta->expr()->eq('con.numero',$numero));
                $consulta->andWhere($consulta->expr()->eq('con.anno',$anno));
                if ($proveedor != "" && $tipo_de_servicio != ""){
                    $consulta->andWhere('pro.nombre LIKE \'%'.$proveedor.'%\' OR prv.nombre LIKE \'%'.$proveedor.'%\'');
                    $consulta->andWhere($consulta->expr()->eq('tds.nombre','\''.$tipo_de_servicio.'\''));
                }
            }else{
                if ($proveedor != "" && $tipo_de_servicio != ""){
                    $consulta->where('pro.nombre LIKE \'%'.$proveedor.'%\' OR prv.nombre LIKE \'%'.$proveedor.'%\'');
                    $consulta->andWhere($consulta->expr()->eq('tds.nombre','\''.$tipo_de_servicio.'\''));
                }else{
                    throw new ORMInvalidArgumentException("Debe especificar los parámetros correctos");
                }
            }
            return $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
        }

        public function getSuplementoDadoNumeroYContrato(int $numero_suplemento,int $id_contrato):array
        {
            $consulta = $this->getEntityManager()->createQueryBuilder()
                ->select('sup.id')
                ->from('App\Entity\Suplemento','sup')
                ->where($this->getEntityManager()->createQueryBuilder()->expr()->eq('sup.numero',$numero_suplemento))
                ->andWhere($this->getEntityManager()->createQueryBuilder()->expr()->eq('sup.contrato',$id_contrato));
            return $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
        }

        public function getContratoPorNumeroYAnno(int $numero, int $anno):array
        {
            return $this->findBy(array('numero'=>$numero,'anno'=>$anno));
        }

        public function getCantidadContratosPorAnno($anno):int
        {
            return count($this->findBy(array('anno'=>$anno)));
        }

        public function getCantidadSuplentosDadoIdContrato(int $id_contrato):int
        {
            $consulta = $this->getEntityManager()->createQueryBuilder()
                ->select('sup.id')
                ->from('App\Entity\Suplemento','sup')
                ->where($this->getEntityManager()->createQueryBuilder()->expr()->eq('sup.contrato',$id_contrato));
            return count($this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult());
        }

        public function getCantidadFacturasDadoIdContrato(int $id_contrato):int
        {
            $consulta = $this->getEntityManager()->createQueryBuilder()
                ->select('fac.id')
                ->from('App\Entity\Factura','fac')
                ->where($this->getEntityManager()->createQueryBuilder()->expr()->eq('fac.contrato',$id_contrato));
            return count($this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult());
        }

    public function getFacturaDadoNumeroYContrato(int $numero_factura,int $id_contrato):array
    {
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('fac.id')
            ->from('App\Entity\Factura','fac')
            ->where($this->getEntityManager()->createQueryBuilder()->expr()->eq('fac.numeroRegistro',$numero_factura))
            ->andWhere($this->getEntityManager()->createQueryBuilder()->expr()->eq('fac.contrato',$id_contrato));
        return $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
    }

    public function updateEjecucionContratoCUPYSaldoCUP(int $id_contrato, float $monto, bool $incremento):int{
        $signo_ejecucion = $incremento == true ? '+' : '-';
        $signo_saldo = $incremento == false ? '+' : '-';
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->update('App\Entity\Contrato','con')
            ->set('con.ejecucionContratoCup', 'con.ejecucionContratoCup '.$signo_ejecucion.' :monto')
            ->set('con.saldoCup', 'con.saldoCup '.$signo_saldo.' :monto')
            ->where('con.id = :id_contrato')
            ->setParameters(array('id_contrato'=>$id_contrato,'monto'=>$monto))
            ->getQuery();
        return $consulta->execute();;
    }
    public function updateEjecucionContratoCUCYSaldoCUC(int $id_contrato, float $monto, bool $incremento):int{
        $signo_ejecucion = $incremento == true ? '+' : '-';
        $signo_saldo = $incremento == false ? '+' : '-';
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->update('App\Entity\Contrato','con')
            ->set('con.ejecucionContratoCuc', 'con.ejecucionContratoCuc '.$signo_ejecucion.' :monto')
            ->set('con.saldoCuc', 'con.saldoCuc '.$signo_saldo.' :monto')
            ->where('con.id = :id_contrato')
            ->setParameters(array('id_contrato'=>$id_contrato,'monto'=>$monto))
            ->getQuery();
        return $consulta->execute();
    }

    public function updateEstado(int $id_contrato, string $motivo, bool $activo):int
    {
        $estado = $activo == true ? 1 : 0;
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->update('App\Entity\Contrato','con')
            ->set('con.estado', ':estado')
            ->set('con.motivoEstado',':motivo')
            ->where('con.id = :id_contrato')
            ->setParameters(array('id_contrato'=>$id_contrato,'motivo'=>$motivo,'estado'=>$estado))
            ->getQuery();
        return $consulta->execute();
    }

    public function removeContratoYDependecias($id_contrato):int
    {
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->delete('App\Entity\Contrato','con')
            ->where('con.id = :id_contrato')
            ->setParameters(array('id_contrato'=>$id_contrato))
            ->getQuery();

        if ($consulta->execute() == 1){
            $consulta = $this->getEntityManager()->createQueryBuilder()
                ->delete('App\Entity\Factura','fac')
                ->where('fac.contrato = :id_contrato')
                ->setParameters(array('id_contrato'=>$id_contrato))
                ->getQuery();
            $consulta->execute();
            $consulta = $this->getEntityManager()->createQueryBuilder()
                ->delete('App\Entity\Suplemento','sup')
                ->where('sup.id = :id_contrato')
                ->setParameters(array('id_contrato'=>$id_contrato))
                ->getQuery();
            $consulta->execute();
            return 1;
        }
        return 0;
    }

    public function getCantidadDependencias(int $id_contrato):int
    {
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(fac.id) AS cantidad')
            ->from('App\Entity\Factura','fac')
            ->where($this->getEntityManager()->createQueryBuilder()->expr()->eq('fac.contrato',$id_contrato));
        $resultado = $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
        return $resultado[0]["cantidad"];
    }

    public function getIdContratoYProveedorDadoNumeroYAnno(int $numero, int $anno):array{
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('con.id')
            ->addSelect('CONCAT(pro.nombre, \'-\' ,prv.nombre) AS proveedor')
            ->from('App\Entity\Contrato','con')
            ->innerJoin('App\Entity\NomProveedor','pro',Expr\Join::WITH,'con.proveedor = pro.id')
            ->innerJoin('App\Entity\NomProvincia','prv', Expr\Join::WITH, 'pro.provincia = prv.id');
             $consulta->where($consulta->expr()->eq('con.numero',$numero));
            $consulta->andWhere($consulta->expr()->eq('con.anno',$anno));
        return $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
    }

    public function getNumeroAnnoProveedorContratoDadoIdContrato(int $id):array
    {
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('con.numero')
            ->addSelect('CONCAT(pro.nombre, \'-\' ,prv.nombre) AS proveedor')
            ->addSelect('con.anno')
            ->from('App\Entity\Contrato','con')
            ->innerJoin('App\Entity\NomProveedor','pro',Expr\Join::WITH,'con.proveedor = pro.id')
            ->innerJoin('App\Entity\NomProvincia','prv', Expr\Join::WITH, 'pro.provincia = prv.id');
        ;
        $consulta->where($consulta->expr()->eq('con.id',$id));
        return $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
    }

    public function getNombreCorreoDeAreaDadoIdContrato(int $id):array{
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('are.nombre')
            ->addSelect('are.correo')
            ->from('App\Entity\Contrato','con')
            ->innerJoin('App\Entity\NomArea','are',Expr\Join::WITH,'con.areaAdministraContrato = are.id');
        $consulta->where($consulta->expr()->eq('con.id',$id));
        return $this->getEntityManager()->createQuery($consulta->getDQL())->getArrayResult();
    }

    public function chequearSaldo(int $id_contrato, RegistryInterface $doctrine){
        $contrato = $this->find($id_contrato);
        $confNotificacionExtension = new ConfNotificacionExtension($doctrine);
        $configuracion = $confNotificacionExtension->getConfNotificacion();
        $insuficiente_cup = $contrato->getSaldoCup() <= $configuracion->getSaldoMinimoNotificacionCup() ? true : false ;
        $insuficiente_cuc = $contrato->getSaldoCuc() <= $configuracion->getSaldoMinimoNotificacionCuc() ? true : false ;

        if($insuficiente_cup){
            $confNotificacionExtension->enviarCorreoNotificacion('saldo_minimo', $contrato, "CUP");
        }

        if($insuficiente_cuc){
            $confNotificacionExtension->enviarCorreoNotificacion('saldo_minimo', $contrato, "CUC");
        }
    }

}