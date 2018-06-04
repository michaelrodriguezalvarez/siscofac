<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class NomBancoRepository extends EntityRepository
{
    public function getParsedFieldFromSelect(): array
    {
        $bancos = $this->findAll();
        $resultado_nombres = array();
        $resultado_ids = array();

        foreach ($bancos as $banco) {
            array_push($resultado_nombres, $banco->getNombre());
            array_push($resultado_ids, $banco->getId());            
        }  

        return array_combine($resultado_nombres,$resultado_ids);         
    }
}