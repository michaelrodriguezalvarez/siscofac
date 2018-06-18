<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Factura
 *
 * @ORM\Table(name="factura")
 * @ORM\Entity
 */
class Factura
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
     * @ORM\Column(name="numero_registro", type="integer", nullable=false)
     */
    private $numero_registro;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_del_proveedor", type="text", length=255, nullable=false)
     */
    private $numero_del_proveedor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var int
     *
     * @ORM\Column(name="tipo_servicio", type="integer", nullable=false)
     */
    private $tipoServicio;

    /**
     * @var string
     *
     * @ORM\Column(name="concepto", type="text", length=65535, nullable=false)
     */
    private $concepto;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_cup", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorCup;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_cuc", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorCuc;

    /**
     * @var int
     *
     * @ORM\Column(name="contrato", type="integer", nullable=false)
     */
    private $contrato;

    /**
     * @var int
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_cheque", type="bigint", nullable=false)
     */
    private $numeroCheque;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_cheque", type="date", nullable=false)
     */
    private $fechaCheque;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroRegistro(): ?int
    {
        return $this->numero_registro;
    }

    public function setNumeroRegistro(int $numero_registro): self
    {
        $this->numero_registro = $numero_registro;

        return $this;
    }

    public function getNumeroDelProveedor(): ?string
    {
        return $this->numero_del_proveedor;
    }

    public function setNumeroDelProveedor(string $numero_del_proveedor): self
    {
        $this->numero_del_proveedor = $numero_del_proveedor;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getTipoServicio(): ?int
    {
        return $this->tipoServicio;
    }

    public function setTipoServicio(int $tipoServicio): self
    {
        $this->tipoServicio = $tipoServicio;

        return $this;
    }

    public function getConcepto(): ?string
    {
        return $this->concepto;
    }

    public function setConcepto(string $concepto): self
    {
        $this->concepto = $concepto;

        return $this;
    }

    public function getValorCup(): ?float
    {
        return $this->valorCup;
    }

    public function setValorCup(float $valorCup): self
    {
        $this->valorCup = $valorCup;

        return $this;
    }

    public function getValorCuc(): ?float
    {
        return $this->valorCuc;
    }

    public function setValorCuc(float $valorCuc): self
    {
        $this->valorCuc = $valorCuc;

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

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getNumeroCheque(): ?int
    {
        return $this->numeroCheque;
    }

    public function setNumeroCheque(int $numeroCheque): self
    {
        $this->numeroCheque = $numeroCheque;

        return $this;
    }

    public function getFechaCheque(): ?\DateTimeInterface
    {
        return $this->fechaCheque;
    }

    public function setFechaCheque(\DateTimeInterface $fechaCheque): self
    {
        $this->fechaCheque = $fechaCheque;

        return $this;
    }


}
