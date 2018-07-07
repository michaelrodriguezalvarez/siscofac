<?php
namespace App\Twig;

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
            new TwigFunction('enviarCorreo', [$this, 'enviarCorreo']),
        ];
    }

    public function getConfNotificacion():ConfNotificacion{
        $em = $this->doctrine->getManager();
        $configuraciones = $em->getRepository(ConfNotificacion::class)->findAll();
        $configuracion = new ConfNotificacion();

        if (count($configuraciones)>0){
            $configuracion = clone $configuraciones[0];
        }else{
            throw new \Exception("Es necesario configurar el correo");
        }
        return $configuracion;
    }

    public function enviarCorreo(array $destinatarios, int $identificador, string $motivo, string $aplicacion):string{
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
                ->setBody($this->getCorreoTextoConDatos($this->configuracion->getCorreoTexto(), $identificador, $motivo, $aplicacion));
            ;

            $this->swiftMailer->send($mensaje);

        }catch(\Swift_TransportException $swfex){
            return "Error: Corrija la configuración para el correo";
        }catch(\Exception $ex){
            return $ex->getMessage();
        }

        return "Mensaje Enviado";
    }

    protected function getCorreoTextoConDatos(string $texto, int $identificador, string $motivo, string $aplicacion):string{
        $texto = str_replace("--identificador--",$identificador,$texto);
        $texto = str_replace("--motivo--",$motivo,$texto);
        $texto = str_replace("--aplicacion--",$aplicacion,$texto);
        return $texto;
    }
}