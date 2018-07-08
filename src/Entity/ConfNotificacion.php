<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfNotificacion
 *
 * @ORM\Table(name="conf_notificacion")
 * @ORM\Entity
 */
class ConfNotificacion
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
     * @ORM\Column(name="correo_nombre", type="string", length=255, nullable=false)
     */
    private $correoNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="correo_direccion", type="string", length=255, nullable=false)
     */
    private $correoDireccion;

    /**
     * @var string
     *
     * @ORM\Column(name="correo_servidor", type="string", length=255, nullable=false)
     */
    private $correoServidor;

    /**
     * @var int
     *
     * @ORM\Column(name="correo_puerto", type="integer", nullable=false)
     */
    private $correoPuerto;

    /**
     * @var string
     *
     * @ORM\Column(name="correo_clave", type="string", length=255, nullable=false)
     */
    private $correoClave;

    /**
     * @var string
     *
     * @ORM\Column(name="correo_asunto", type="string", length=255, nullable=false)
     */
    private $correoAsunto;

    /**
     * @var string
     *
     * @ORM\Column(name="correo_texto_contrato_inhabilitado", type="text", length=65535, nullable=false)
     */
    private $correoTextoContratoInhabilitado;

    /**
     * @var int
     *
     * @ORM\Column(name="dias_minimo_notificacion", type="integer", nullable=false)
     */
    private $diasMinimoNotificacion;

    /**
     * @var float
     *
     * @ORM\Column(name="saldo_minimo_notificacion_cup", type="float", precision=255, scale=0, nullable=false)
     */
    private $saldoMinimoNotificacionCup;

    /**
     * @var float
     *
     * @ORM\Column(name="saldo_minimo_notificacion_cuc", type="float", precision=255, scale=0, nullable=false)
     */
    private $saldoMinimoNotificacionCuc;

    /**
     * @var string
     *
     * @ORM\Column(name="correo_texto_saldo_minimo", type="text", length=65535, nullable=false)
     */
    private $correoTextoSaldoMinimo;

    /**
     * @var string
     *
     * @ORM\Column(name="correo_texto_tiempo_minimo", type="text", length=65535, nullable=false)
     */
    private $correoTextoTiempoMinimo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCorreoNombre(): ?string
    {
        return $this->correoNombre;
    }

    public function setCorreoNombre(string $correoNombre): self
    {
        $this->correoNombre = $correoNombre;

        return $this;
    }

    public function getCorreoDireccion(): ?string
    {
        return $this->correoDireccion;
    }

    public function setCorreoDireccion(string $correoDireccion): self
    {
        $this->correoDireccion = $correoDireccion;

        return $this;
    }

    public function getCorreoServidor(): ?string
    {
        return $this->correoServidor;
    }

    public function setCorreoServidor(string $correoServidor): self
    {
        $this->correoServidor = $correoServidor;

        return $this;
    }

    public function getCorreoPuerto(): ?int
    {
        return $this->correoPuerto;
    }

    public function setCorreoPuerto(int $correoPuerto): self
    {
        $this->correoPuerto = $correoPuerto;

        return $this;
    }

    public function getCorreoClave(): ?string
    {
        return $this->correoClave;
    }

    public function setCorreoClave(string $correoClave): self
    {
        $this->correoClave = $correoClave;

        return $this;
    }

    public function getCorreoAsunto(): ?string
    {
        return $this->correoAsunto;
    }

    public function setCorreoAsunto(string $correoAsunto): self
    {
        $this->correoAsunto = $correoAsunto;

        return $this;
    }

    public function getCorreoTextoContratoInhabilitado(): ?string
    {
        return $this->correoTextoContratoInhabilitado;
    }

    public function setCorreoTextoContratoInhabilitado(string $correoTextoContratoInhabilitado): self
    {
        $this->correoTextoContratoInhabilitado = $correoTextoContratoInhabilitado;

        return $this;
    }

    public function getDiasMinimoNotificacion(): ?int
    {
        return $this->diasMinimoNotificacion;
    }

    public function setDiasMinimoNotificacion(int $diasMinimoNotificacion): self
    {
        $this->diasMinimoNotificacion = $diasMinimoNotificacion;

        return $this;
    }

    public function getSaldoMinimoNotificacionCup(): ?float
    {
        return $this->saldoMinimoNotificacionCup;
    }

    public function setSaldoMinimoNotificacionCup(float $saldoMinimoNotificacionCup): self
    {
        $this->saldoMinimoNotificacionCup = $saldoMinimoNotificacionCup;

        return $this;
    }

    public function getSaldoMinimoNotificacionCuc(): ?float
    {
        return $this->saldoMinimoNotificacionCuc;
    }

    public function setSaldoMinimoNotificacionCuc(float $saldoMinimoNotificacionCuc): self
    {
        $this->saldoMinimoNotificacionCuc = $saldoMinimoNotificacionCuc;

        return $this;
    }

    public function getCorreoTextoSaldoMinimo(): ?string
    {
        return $this->correoTextoSaldoMinimo;
    }

    public function setCorreoTextoSaldoMinimo(string $correoTextoSaldoMinimo): self
    {
        $this->correoTextoSaldoMinimo = $correoTextoSaldoMinimo;

        return $this;
    }

    public function getCorreoTextoTiempoMinimo(): ?string
    {
        return $this->correoTextoTiempoMinimo;
    }

    public function setCorreoTextoTiempoMinimo(string $correoTextoTiempoMinimo): self
    {
        $this->correoTextoTiempoMinimo = $correoTextoTiempoMinimo;

        return $this;
    }


}
