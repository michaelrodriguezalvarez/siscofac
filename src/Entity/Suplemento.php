<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Suplemento
 *
 * @ORM\Table(name="suplemento")
 * @ORM\Entity
 */
class Suplemento
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
     * @ORM\Column(name="numero", type="integer", nullable=false)
     */
    private $numero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_terminacion", type="date", nullable=false)
     */
    private $fechaTerminacion;

    /**
     * @var int
     *
     * @ORM\Column(name="acuerdo", type="integer", nullable=false)
     */
    private $acuerdo;

    /**
     * @var int
     *
     * @ORM\Column(name="contrato", type="integer", nullable=false)
     */
    private $contrato;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fechaInicio): self
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getFechaTerminacion(): ?\DateTimeInterface
    {
        return $this->fechaTerminacion;
    }

    public function setFechaTerminacion(\DateTimeInterface $fechaTerminacion): self
    {
        $this->fechaTerminacion = $fechaTerminacion;

        return $this;
    }

    public function getAcuerdo(): ?int
    {
        return $this->acuerdo;
    }

    public function setAcuerdo(int $acuerdo): self
    {
        $this->acuerdo = $acuerdo;

        return $this;
    }

    public function getContrato(): ?int
    {
        return $this->contrato;
    }

    public function setContrato(int $contrato): self
    {
        $this->contrato = $contrato;

        return $this;
    }


}
