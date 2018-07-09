<?php
namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Contrato;
use App\Twig\ConfNotificacionExtension;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ContratoListener
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Contrato) {
            $this->chequearSaldo($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Contrato) {
            $this->chequearSaldo($entity);
        }
    }

    protected function chequearSaldo(Contrato $contrato){
        $confNotificacionExtension = new ConfNotificacionExtension($this->doctrine);
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