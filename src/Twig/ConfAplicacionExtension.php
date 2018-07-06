<?php
namespace App\Twig;

use App\Entity\ConfAplicacion;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ConfAplicacionExtension extends AbstractExtension
{
    protected $doctrine;
    protected $configuracion_aplicacion;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->configuracion_aplicacion = $this->getConfAplicacion();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getNombreAplicacion', [$this, 'getNombreAplicacion']),
            new TwigFunction('getSlogan', [$this, 'getSlogan']),
            new TwigFunction('getIdiomaPorDefecto', [$this, 'getIdiomaPorDefecto']),
        ];
    }

    public function getNombreAplicacion():string
    {
        return $this->configuracion_aplicacion->getNombreAplicacion();
    }

    public function getSlogan():string
    {
        return $this->configuracion_aplicacion->getSlogan()();
    }

    public function getIdiomaPorDefecto():string
    {
        return $this->configuracion_aplicacion->getIdiomaPorDefecto();
    }

    protected function getConfAplicacion():ConfAplicacion{
        $em = $this->doctrine->getManager();
        $configuraciones = $em->getRepository(ConfAplicacion::class)->findAll();
        $conficuracion = new ConfAplicacion();

        if (count($configuraciones)==0){
            $conficuracion->setNombreAplicacion(strtoupper("Siscofac"));
            $conficuracion->setSlogan("La gestión segura de los contratos");
            $conficuracion->setIdiomaPorDefecto("es");
        }else{
            $conficuracion = clone $configuraciones[0];
            $conficuracion->setNombreAplicacion(strtoupper($conficuracion->getNombreAplicacion()));
        }
        return $conficuracion;
    }
}