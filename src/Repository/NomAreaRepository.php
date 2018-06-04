<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class NomAreaRepository extends EntityRepository
{
    public function getParsedFieldFromSelect(): array
    {
        $areas = $this->findAll();
        $resultado_nombres = array();
        $resultado_ids = array();

        foreach ($areas as $area) {
            array_push($resultado_nombres, $area->getNombre());
            array_push($resultado_ids, $area->getId());            
        }  

        return array_combine($resultado_nombres,$resultado_ids);         
    }
}