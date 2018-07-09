<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NomProveedor
 *
 * @ORM\Table(name="nom_proveedor")
 * @ORM\Entity(repositoryClass="App\Repository\NomProveedorRepository")
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="provincia", type="integer", nullable=false)
     */
    private $provincia;

    /**
     * @var string|null
     *
     * @ORM\Column(name="direccion", type="text", length=65535, nullable=true, options={"default"="N/A"})
     */
    private $direccion = 'N/A';

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true, options={"default"="N/A"})
     */
    private $telefono = 'N/A';

    /**
     * @var string|null
     *
     * @ORM\Column(name="correo", type="string", length=255, nullable=true, options={"default"="N/A"})
     */
    private $correo = 'N/A';

    /**
     * @var string|null
     *
     * @ORM\Column(name="organismo", type="string", length=255, nullable=true, options={"default"="N/A"})
     */
    private $organismo = 'N/A';

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

    public function getOrganismo(): ?string
    {
        return $this->organismo;
    }

    public function setOrganismo(?string $organismo): self
    {
        $this->organismo = $organismo;

        return $this;
    }

    public function __toString()
    {
        return $this->getNombre();
    }

}
