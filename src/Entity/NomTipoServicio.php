<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NomTipoServicio
 *
 * @ORM\Table(name="nom_tipo_servicio")
 * @ORM\Entity(repositoryClass="App\Repository\NomTipoServicioRepository")
 */
class NomTipoServicio
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }


}
