<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NomProveedor
 *
 * @ORM\Table(name="nom_proveedor")
 * @ORM\Entity
 */
class NomProveedor
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
     * @var int
     *
     * @ORM\Column(name="provincia", type="integer", nullable=false)
     */
    private $provincia;

    /**
     * @var string|null
     *
     * @ORM\Column(name="direccion", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    private $direccion = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $telefono = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="correo", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $correo = 'NULL';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvincia(): ?int
    {
        return $this->provincia;
    }

    public function setProvincia(int $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(?string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }


}
