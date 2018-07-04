<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SuplementoComiteContratacion
 *
 * @ORM\Table(name="suplemento_comite_contratacion")
 * @ORM\Entity(repositoryClass="App\Repository\SuplementoComiteContratacionRepository")
 */
class SuplementoComiteContratacion
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
     * @ORM\Column(name="contrato", type="integer", nullable=false)
     */
    private $contrato;

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
     * @ORM\Column(name="valor_cup", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorCup;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_cuc", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorCuc;

    public function getId(): ?int
    {
        return $this->id;
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


}
