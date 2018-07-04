<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContratoComiteContratacion
 *
 * @ORM\Table(name="contrato_comite_contratacion")
 * @ORM\Entity(repositoryClass="App\Repository\ContratoComiteContratacionRepository")
 */
class ContratoComiteContratacion
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
     * @ORM\Column(name="proveedor", type="integer", nullable=false)
     */
    private $proveedor;

    /**
     * @var int
     *
     * @ORM\Column(name="tipo_de_servicio", type="integer", nullable=false)
     */
    private $tipoDeServicio;

    /**
     * @var string
     *
     * @ORM\Column(name="objeto", type="text", length=65535, nullable=false)
     */
    private $objeto;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_contrato_inicial_cup", type="float", precision=255, scale=0, nullable=false)
     */
    private $valorContratoInicialCup;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_contrato_inicial_cuc", type="float", precision=255, scale=0, nullable=false)
     */
    private $valorContratoInicialCuc;

    /**
     * @var int
     *
     * @ORM\Column(name="area_administra_contrato", type="integer", nullable=false)
     */
    private $areaAdministraContrato;

    /**
     * @var int
     *
     * @ORM\Column(name="orden", type="integer", nullable=false)
     */
    private $orden;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_de_reunion", type="date", nullable=false)
     */
    private $fechaDeReunion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProveedor(): ?int
    {
        return $this->proveedor;
    }

    public function setProveedor(int $proveedor): self
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    public function getTipoDeServicio(): ?int
    {
        return $this->tipoDeServicio;
    }

    public function setTipoDeServicio(int $tipoDeServicio): self
    {
        $this->tipoDeServicio = $tipoDeServicio;

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

    public function getValorContratoInicialCup(): ?float
    {
        return $this->valorContratoInicialCup;
    }

    public function setValorContratoInicialCup(float $valorContratoInicialCup): self
    {
        $this->valorContratoInicialCup = $valorContratoInicialCup;

        return $this;
    }

    public function getValorContratoInicialCuc(): ?float
    {
        return $this->valorContratoInicialCuc;
    }

    public function setValorContratoInicialCuc(float $valorContratoInicialCuc): self
    {
        $this->valorContratoInicialCuc = $valorContratoInicialCuc;

        return $this;
    }

    public function getAreaAdministraContrato(): ?int
    {
        return $this->areaAdministraContrato;
    }

    public function setAreaAdministraContrato(int $areaAdministraContrato): self
    {
        $this->areaAdministraContrato = $areaAdministraContrato;

        return $this;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    public function getFechaDeReunion(): ?\DateTimeInterface
    {
        return $this->fechaDeReunion;
    }

    public function setFechaDeReunion(\DateTimeInterface $fechaDeReunion): self
    {
        $this->fechaDeReunion = $fechaDeReunion;

        return $this;
    }


}
