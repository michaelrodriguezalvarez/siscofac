<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrato
 *
 * @ORM\Table(name="contrato")
 * @ORM\Entity
 */
class Contrato
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
     * @ORM\Column(name="anno", type="date", nullable=false)
     */
    private $anno;

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
     * @var string
     *
     * @ORM\Column(name="nit", type="string", length=255, nullable=false)
     */
    private $nit;

    /**
     * @var int
     *
     * @ORM\Column(name="reeup", type="bigint", nullable=false)
     */
    private $reeup;

    /**
     * @var int
     *
     * @ORM\Column(name="carnet_identidad", type="integer", nullable=false)
     */
    private $carnetIdentidad;

    /**
     * @var int
     *
     * @ORM\Column(name="tipo_de_persona", type="integer", nullable=false)
     */
    private $tipoDePersona;

    /**
     * @var int
     *
     * @ORM\Column(name="cuenta_bancaria_cup", type="bigint", nullable=false)
     */
    private $cuentaBancariaCup;

    /**
     * @var int
     *
     * @ORM\Column(name="cuenta_bancaria_cuc", type="bigint", nullable=false)
     */
    private $cuentaBancariaCuc;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_contrato_inicial_cup", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorContratoInicialCup;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_contrato_inicial_cuc", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorContratoInicialCuc;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_contrato_total_cup", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorContratoTotalCup;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_contrato_total_cuc", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorContratoTotalCuc;

    /**
     * @var float
     *
     * @ORM\Column(name="ejecucion_contrato_cup", type="float", precision=10, scale=0, nullable=false)
     */
    private $ejecucionContratoCup;

    /**
     * @var float
     *
     * @ORM\Column(name="ejecucion_contrato_cuc", type="float", precision=10, scale=0, nullable=false)
     */
    private $ejecucionContratoCuc;

    /**
     * @var float
     *
     * @ORM\Column(name="saldo_cup", type="float", precision=10, scale=0, nullable=false)
     */
    private $saldoCup;

    /**
     * @var float
     *
     * @ORM\Column(name="saldo_cuc", type="float", precision=10, scale=0, nullable=false)
     */
    private $saldoCuc;

    /**
     * @var int
     *
     * @ORM\Column(name="banco", type="integer", nullable=false)
     */
    private $banco;

    /**
     * @var int
     *
     * @ORM\Column(name="aprob_contrato_comite_contratacion", type="integer", nullable=false)
     */
    private $aprobContratoComiteContratacion;

    /**
     * @var int
     *
     * @ORM\Column(name="aprob_contrato_comite_administracion", type="integer", nullable=false)
     */
    private $aprobContratoComiteAdministracion;

    /**
     * @var int
     *
     * @ORM\Column(name="area_administra_contrato", type="integer", nullable=false)
     */
    private $areaAdministraContrato;

    /**
     * @var int
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

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

    public function getAnno(): ?\DateTimeInterface
    {
        return $this->anno;
    }

    public function setAnno(\DateTimeInterface $anno): self
    {
        $this->anno = $anno;

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

    public function getNit(): ?string
    {
        return $this->nit;
    }

    public function setNit(string $nit): self
    {
        $this->nit = $nit;

        return $this;
    }

    public function getReeup(): ?int
    {
        return $this->reeup;
    }

    public function setReeup(int $reeup): self
    {
        $this->reeup = $reeup;

        return $this;
    }

    public function getCarnetIdentidad(): ?int
    {
        return $this->carnetIdentidad;
    }

    public function setCarnetIdentidad(int $carnetIdentidad): self
    {
        $this->carnetIdentidad = $carnetIdentidad;

        return $this;
    }

    public function getTipoDePersona(): ?int
    {
        return $this->tipoDePersona;
    }

    public function setTipoDePersona(int $tipoDePersona): self
    {
        $this->tipoDePersona = $tipoDePersona;

        return $this;
    }

    public function getCuentaBancariaCup(): ?int
    {
        return $this->cuentaBancariaCup;
    }

    public function setCuentaBancariaCup(int $cuentaBancariaCup): self
    {
        $this->cuentaBancariaCup = $cuentaBancariaCup;

        return $this;
    }

    public function getCuentaBancariaCuc(): ?int
    {
        return $this->cuentaBancariaCuc;
    }

    public function setCuentaBancariaCuc(int $cuentaBancariaCuc): self
    {
        $this->cuentaBancariaCuc = $cuentaBancariaCuc;

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

    public function getValorContratoTotalCup(): ?float
    {
        return $this->valorContratoTotalCup;
    }

    public function setValorContratoTotalCup(float $valorContratoTotalCup): self
    {
        $this->valorContratoTotalCup = $valorContratoTotalCup;

        return $this;
    }

    public function getValorContratoTotalCuc(): ?float
    {
        return $this->valorContratoTotalCuc;
    }

    public function setValorContratoTotalCuc(float $valorContratoTotalCuc): self
    {
        $this->valorContratoTotalCuc = $valorContratoTotalCuc;

        return $this;
    }

    public function getEjecucionContratoCup(): ?float
    {
        return $this->ejecucionContratoCup;
    }

    public function setEjecucionContratoCup(float $ejecucionContratoCup): self
    {
        $this->ejecucionContratoCup = $ejecucionContratoCup;

        return $this;
    }

    public function getEjecucionContratoCuc(): ?float
    {
        return $this->ejecucionContratoCuc;
    }

    public function setEjecucionContratoCuc(float $ejecucionContratoCuc): self
    {
        $this->ejecucionContratoCuc = $ejecucionContratoCuc;

        return $this;
    }

    public function getSaldoCup(): ?float
    {
        return $this->saldoCup;
    }

    public function setSaldoCup(float $saldoCup): self
    {
        $this->saldoCup = $saldoCup;

        return $this;
    }

    public function getSaldoCuc(): ?float
    {
        return $this->saldoCuc;
    }

    public function setSaldoCuc(float $saldoCuc): self
    {
        $this->saldoCuc = $saldoCuc;

        return $this;
    }

    public function getBanco(): ?int
    {
        return $this->banco;
    }

    public function setBanco(int $banco): self
    {
        $this->banco = $banco;

        return $this;
    }

    public function getAprobContratoComiteContratacion(): ?int
    {
        return $this->aprobContratoComiteContratacion;
    }

    public function setAprobContratoComiteContratacion(int $aprobContratoComiteContratacion): self
    {
        $this->aprobContratoComiteContratacion = $aprobContratoComiteContratacion;

        return $this;
    }

    public function getAprobContratoComiteAdministracion(): ?int
    {
        return $this->aprobContratoComiteAdministracion;
    }

    public function setAprobContratoComiteAdministracion(int $aprobContratoComiteAdministracion): self
    {
        $this->aprobContratoComiteAdministracion = $aprobContratoComiteAdministracion;

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

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }


}
