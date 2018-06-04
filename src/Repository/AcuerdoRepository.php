<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class AcuerdoRepository extends EntityRepository
{
    public function getParsedFieldFromSelect(): array
    {
        $acuerdos = $this->findAll();
        $resultado_nombres = array();
        $resultado_ids = array();

        foreach ($acuerdos as $acuerdo) {
            array_push($resultado_nombres, $acuerdo);
            array_push($resultado_ids, $acuerdo->getId());            
        }  

        return array_combine($resultado_nombres,$resultado_ids);         
    }
}