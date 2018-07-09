<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfAplicacion
 *
 * @ORM\Table(name="conf_aplicacion")
 * @ORM\Entity
 */
class ConfAplicacion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_aplicacion", type="string", length=255, nullable=false)
     */
    private $nombreAplicacion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="slogan", type="string", length=255, nullable=true, options={"default"="N/A"})
     */
    private $slogan = 'N/A';

    /**
     * @var string
     *
     * @ORM\Column(name="idioma_por_defecto", type="string", length=10, nullable=false)
     */
    private $idiomaPorDefecto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreAplicacion(): ?string
    {
        return $this->nombreAplicacion;
    }

    public function setNombreAplicacion(string $nombreAplicacion): self
    {
        $this->nombreAplicacion = $nombreAplicacion;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): self
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getIdiomaPorDefecto(): ?string
    {
        return $this->idiomaPorDefecto;
    }

    public function setIdiomaPorDefecto(string $idiomaPorDefecto): self
    {
        $this->idiomaPorDefecto = $idiomaPorDefecto;

        return $this;
    }


}
