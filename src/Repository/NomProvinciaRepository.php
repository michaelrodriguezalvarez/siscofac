<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class NomProvinciaRepository extends EntityRepository
{
    public function getParsedFieldFromSelect()
    {
        $provincias = $this->findAll();
        $resultado_nombres = array();
        $resultado_ids = array();

        foreach ($provincias as $provincia) {
        	array_push($resultado_nombres, $provincia->getNombre());
            array_push($resultado_ids, $provincia->getId());            
        }  

        return array_combine($resultado_nombres,$resultado_ids);         
    }
}