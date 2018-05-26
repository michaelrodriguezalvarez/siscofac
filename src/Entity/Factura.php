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
     * @var int|null
     *
     * @ORM\Column(name="numero", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $numero = 'NULL';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha", type="date", nullable=true, options={"default"="NULL"})
     */
    private $fecha = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="tipo_servicio", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $tipoServicio = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="concepto", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    private $concepto = 'NULL';

    /**
     * @var float|null
     *
     * @ORM\Column(name="valor_cup", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     */
    private $valorCup = 'NULL';

    /**
     * @var float|null
     *
     * @ORM\Column(name="valor_cuc", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     */
    private $valorCuc = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="contrato", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $contrato = 'NULL';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getTipoServicio(): ?int
    {
        return $this->tipoServicio;
    }

    public function setTipoServicio(?int $tipoServicio): self
    {
        $this->tipoServicio = $tipoServicio;

        return $this;
    }

    public function getConcepto(): ?string
    {
        return $this->concepto;
    }

    public function setConcepto(?string $concepto): self
    {
        $this->concepto = $concepto;

        return $this;
    }

    public function getValorCup(): ?float
    {
        return $this->valorCup;
    }

    public function setValorCup(?float $valorCup): self
    {
        $this->valorCup = $valorCup;

        return $this;
    }

    public function getValorCuc(): ?float
    {
        return $this->valorCuc;
    }

    public function setValorCuc(?float $valorCuc): self
    {
        $this->valorCuc = $valorCuc;

        return $this;
    }

    public function getContrato(): ?int
    {
        return $this->contrato;
    }

    public function setContrato(?int $contrato): self
    {
        $this->contrato = $contrato;

        return $this;
    }


}
