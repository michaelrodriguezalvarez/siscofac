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
     * @var string
     *
     * @ORM\Column(name="objeto", type="text", length=65535, nullable=false)
     */
    private $objeto;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_suplemento_cup", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorSuplementoCup;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_suplemento_cuc", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorSuplementoCuc;

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
     * @ORM\Column(name="numero_acuerdo", type="integer", nullable=false)
     */
    private $numeroAcuerdo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_acuerdo", type="date", nullable=false)
     */
    private $fechaAcuerdo;

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

    public function getObjeto(): ?string
    {
        return $this->objeto;
    }

    public function setObjeto(string $objeto): self
    {
        $this->objeto = $objeto;

        return $this;
    }

    public function getValorSuplementoCup(): ?float
    {
        return $this->valorSuplementoCup;
    }

    public function setValorSuplementoCup(float $valorSuplementoCup): self
    {
        $this->valorSuplementoCup = $valorSuplementoCup;

        return $this;
    }

    public function getValorSuplementoCuc(): ?float
    {
        return $this->valorSuplementoCuc;
    }

    public function setValorSuplementoCuc(float $valorSuplementoCuc): self
    {
        $this->valorSuplementoCuc = $valorSuplementoCuc;

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

    public function getNumeroAcuerdo(): ?int
    {
        return $this->numeroAcuerdo;
    }

    public function setNumeroAcuerdo(int $numeroAcuerdo): self
    {
        $this->numeroAcuerdo = $numeroAcuerdo;

        return $this;
    }

    public function getFechaAcuerdo(): ?\DateTimeInterface
    {
        return $this->fechaAcuerdo;
    }

    public function setFechaAcuerdo(\DateTimeInterface $fechaAcuerdo): self
    {
        $this->fechaAcuerdo = $fechaAcuerdo;

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
