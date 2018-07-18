<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrato
 *
 * @ORM\Table(name="contrato")
 * @ORM\Entity(repositoryClass="App\Repository\ContratoRepository")
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
     * @var int
     *
     * @ORM\Column(name="anno", type="integer", nullable=false)
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
     * @var string
     *
     * @ORM\Column(name="reeup", type="string", length=255, nullable=false)
     */
    private $reeup;

    /**
     * @var string
     *
     * @ORM\Column(name="carnet_identidad", type="string", length=255, nullable=false)
     */
    private $carnetIdentidad;

    /**
     * @var int
     *
     * @ORM\Column(name="tipo_de_persona", type="integer", nullable=false)
     */
    private $tipoDePersona;

    /**
     * @var string
     *
     * @ORM\Column(name="cuenta_bancaria_cup", type="string", length=255, nullable=false)
     */
    private $cuentaBancariaCup;

    /**
     * @var string
     *
     * @ORM\Column(name="cuenta_bancaria_cuc", type="string", length=255, nullable=false)
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
     * @var string
     *
     * @ORM\Column(name="forma_de_pago", type="string", length=255, nullable=false)
     */
    private $formaDePago;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_aprob_contrato_comite_contratacion", type="string", length=255, nullable=false)
     */
    private $numeroAprobContratoComiteContratacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_aprob_contrato_comite_contratacion", type="date", nullable=false)
     */
    private $fechaAprobContratoComiteContratacion;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_aprob_contrato_comite_administracion", type="string", length=255, nullable=false)
     */
    private $numeroAprobContratoComiteAdministracion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_aprob_contrato_comite_administracion", type="date", nullable=false)
     */
    private $fechaAprobContratoComiteAdministracion;

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

    /**
     * @var string|null
     *
     * @ORM\Column(name="motivo_estado", type="text", length=65535, nullable=true, options={"default"="N/A"})
     */
    private $motivoEstado = 'N/A';

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

    public function getAnno(): ?int
    {
        return $this->anno;
    }

    public function setAnno(int $anno): self
    {
        $this->anno = $anno;

        return $this;
    }

    public function getFechaInicio(): ?string
    {
        return $this->fechaInicio->format('d-m-Y');
    }

    public function setFechaInicio(string $fechaInicio): self
    {
        $this->fechaInicio = \DateTime::createFromFormat('d-m-Y', $fechaInicio);

        return $this;
    }

    public function getFechaTerminacion(): ?string
    {
        return $this->fechaTerminacion->format('d-m-Y');
    }

    public function setFechaTerminacion(string $fechaTerminacion): self
    {
        $this->fechaTerminacion = \DateTime::createFromFormat('d-m-Y', $fechaTerminacion);

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

    public function getReeup(): ?string
    {
        return $this->reeup;
    }

    public function setReeup(string $reeup): self
    {
        $this->reeup = $reeup;

        return $this;
    }

    public function getCarnetIdentidad(): ?string
    {
        return $this->carnetIdentidad;
    }

    public function setCarnetIdentidad(string $carnetIdentidad): self
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

    public function getCuentaBancariaCup(): ?string
    {
        return $this->cuentaBancariaCup;
    }

    public function setCuentaBancariaCup(string $cuentaBancariaCup): self
    {
        $this->cuentaBancariaCup = $cuentaBancariaCup;

        return $this;
    }

    public function getCuentaBancariaCuc(): ?string
    {
        return $this->cuentaBancariaCuc;
    }

    public function setCuentaBancariaCuc(string $cuentaBancariaCuc): self
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

    public function getFormaDePago(): ?string
    {
        return $this->formaDePago;
    }

    public function setFormaDePago(string $formaDePago): self
    {
        $this->formaDePago = $formaDePago;

        return $this;
    }

    public function getNumeroAprobContratoComiteContratacion(): ?string
    {
        return $this->numeroAprobContratoComiteContratacion;
    }

    public function setNumeroAprobContratoComiteContratacion(string $numeroAprobContratoComiteContratacion): self
    {
        $this->numeroAprobContratoComiteContratacion = $numeroAprobContratoComiteContratacion;

        return $this;
    }

    public function getFechaAprobContratoComiteContratacion(): ?string
    {
        return $this->fechaAprobContratoComiteContratacion->format('d-m-Y');
    }

    public function setFechaAprobContratoComiteContratacion(string $fechaAprobContratoComiteContratacion): self
    {
        $this->fechaAprobContratoComiteContratacion = \DateTime::createFromFormat('d-m-Y', $fechaAprobContratoComiteContratacion);

        return $this;
    }

    public function getNumeroAprobContratoComiteAdministracion(): ?string
    {
        return $this->numeroAprobContratoComiteAdministracion;
    }

    public function setNumeroAprobContratoComiteAdministracion(string $numeroAprobContratoComiteAdministracion): self
    {
        $this->numeroAprobContratoComiteAdministracion = $numeroAprobContratoComiteAdministracion;

        return $this;
    }

    public function getFechaAprobContratoComiteAdministracion(): ?string
    {
        return $this->fechaAprobContratoComiteAdministracion->format('d-m-Y');
    }

    public function setFechaAprobContratoComiteAdministracion(string $fechaAprobContratoComiteAdministracion): self
    {
        $this->fechaAprobContratoComiteAdministracion = \DateTime::createFromFormat('d-m-Y', $fechaAprobContratoComiteAdministracion);

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

    public function getMotivoEstado(): ?string
    {
        return $this->motivoEstado;
    }

    public function setMotivoEstado(?string $motivoEstado): self
    {
        $this->motivoEstado = $motivoEstado;

        return $this;
    }

    public function __toString(): string
    {
       return $this->numero ."/".$this->anno;
    }

}
