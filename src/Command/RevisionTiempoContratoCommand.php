<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Controller\ConfNotificacionController;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class RevisionTiempoContratoCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected static $defaultName = 'RevisionTiempoContrato';

    protected function configure()
    {
        $this
            ->setDescription('Notifica cuando los contratos están próximos a su fecha de terminación')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->note("Buscando contratos que están próximos a su fecha de terminación");
        $confNotificacionController = new ConfNotificacionController();
        $doctrine = $this->container->get('doctrine');
        $reporte = $confNotificacionController->chequearTiempo($doctrine);
        if ($reporte == "No todas las notificaciones fueron enviadas. Revise la configuración del correo."){
            $io->error($reporte);
        }else{
            $io->success($reporte);
        }
    }
}
