<?php
namespace App\Twig;

use App\Entity\Contrato;
use App\Entity\ConfNotificacion;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ConfNotificacionExtension extends AbstractExtension
{
    protected $doctrine;
    protected $swiftMailer;
    protected $configuracion;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getConfNotificacion', [$this, 'getConfNotificacion']),
            new TwigFunction('enviarCorreoSimple', [$this, 'enviarCorreoSimple']),
        ];
    }

    public function getConfNotificacion():ConfNotificacion{
        $em = $this->doctrine->getManager();
        $configuraciones = $em->getRepository(ConfNotificacion::class)->findAll();
        $configuracion = new ConfNotificacion();

        if (count($configuraciones)>0){
            $configuracion = clone $configuraciones[0];
        }else{
            throw new \Exception("Error: Es necesario configurar el correo");
        }
        return $configuracion;
    }

    public function enviarCorreoNotificacion(string $tipo, Contrato $contrato, string $moneda = "", array $destinatarios_extra = []):string{
        $nomArea = $this->doctrine->getRepository(Contrato::class)
            ->getNombreCorreoDeAreaDadoIdContrato($contrato->getId());

        $destinatarios = array($nomArea[0]["correo"] => $nomArea[0]["nombre"]);

        $destinatarios = array_merge($destinatarios,$destinatarios_extra);

        $confAplicacionExtension = new ConfAplicacionExtension($this->doctrine);

        try{
            $this->configuracion = $this->getConfNotificacion();
            $transport = (new \Swift_SmtpTransport($this->configuracion->getCorreoServidor(), $this->configuracion->getCorreoPuerto()))
                ->setUsername($this->configuracion->getCorreoDireccion())
                ->setPassword($this->configuracion->getCorreoClave())
            ;
            $this->swiftMailer = new \Swift_Mailer($transport);

            $texto = "";
            $saldo_minimo = 0.0;

            switch ($tipo){
                case 'saldo_insuficiente':
                    $texto = $this->configuracion->getCorreoTextoContratoInhabilitado();
                    break;
                case 'definido_por_usuario':
                    $texto = $this->configuracion->getCorreoTextoContratoInhabilitado();
                    break;
                case 'saldo_minimo':
                    $texto = $this->configuracion->getCorreoTextoSaldoMinimo();
                    if($moneda == "CUP"){
                        $saldo_minimo = $this->configuracion->getSaldoMinimoNotificacionCup();
                    }else{
                        $saldo_minimo = $this->configuracion->getSaldoMinimoNotificacionCuc();
                    }
                    break;
                case 'tiempo_minimo':
                    $texto = $this->configuracion->getCorreoTextoTiempoMinimo();
                    break;
            }

            $mensaje = (new \Swift_Message($this->configuracion->getCorreoAsunto()))
                ->setFrom([$this->configuracion->getCorreoDireccion() => $this->configuracion->getCorreoNombre()])
                ->setTo($destinatarios)
                ->setBody($this->getCorreoTextoConDatos($texto, $contrato, $confAplicacionExtension->getNombreAplicacion(), $saldo_minimo, $this->configuracion->getDiasMinimoNotificacion(),$moneda));
            ;
            $this->swiftMailer->send($mensaje);

        }catch(\Swift_TransportException $swfex){
            return "Error: Corrija la configuración para el correo";
        }catch(\Exception $ex){
            return $ex->getMessage();
        }

        return "Notificación Enviada Satisfactoriamente";
    }

    protected function getCorreoTextoConDatos(string $texto, Contrato $contrato, string $aplicacion, string $limite, string $tiempo, string $moneda):string{
        $texto = str_replace("--identificador--",$contrato,$texto);
        $texto = str_replace("--motivo--",$contrato->getMotivoEstado(),$texto);
        $texto = str_replace("--aplicacion--",$aplicacion,$texto);
        $texto = str_replace("--moneda--",$moneda,$texto);
        if ($moneda == "CUP"){
            $texto = str_replace("--saldo--",$contrato->getSaldoCup(),$texto);
        }else{
            if ($moneda == "CUC"){
                $texto = str_replace("--saldo--",$contrato->getSaldoCuc(),$texto);
            }
        }
        $texto = str_replace("--limite--",$limite,$texto);
        $texto = str_replace("--tiempo--",$tiempo,$texto);
        $texto = str_replace("--fecha--",$contrato->getFechaTerminacion()->format('d-m-Y'),$texto);
        return $texto;
    }
    public function enviarCorreoSimple(array $destinatarios, string $texto):string {
        try{
            $this->configuracion = $this->getConfNotificacion();
            $transport = (new \Swift_SmtpTransport($this->configuracion->getCorreoServidor(), $this->configuracion->getCorreoPuerto()))
                ->setUsername($this->configuracion->getCorreoDireccion())
                ->setPassword($this->configuracion->getCorreoClave())
            ;
            $this->swiftMailer = new \Swift_Mailer($transport);

            $mensaje = (new \Swift_Message($this->configuracion->getCorreoAsunto()))
                ->setFrom([$this->configuracion->getCorreoDireccion() => $this->configuracion->getCorreoNombre()])
                ->setTo($destinatarios)
                ->setBody($texto);
            ;

            $this->swiftMailer->send($mensaje);

        }catch(\Swift_TransportException $swfex){
            return "Error: Corrija la configuración para el correo";
        }catch(\Exception $ex){
            return $ex->getMessage();
        }

        return "Notificación Enviada Satisfactoriamente";
    }
}