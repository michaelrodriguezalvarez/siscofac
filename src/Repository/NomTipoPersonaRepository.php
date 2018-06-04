<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class NomTipoPersonaRepository extends EntityRepository
{
    public function getParsedFieldFromSelect(): array
    {
        $tipos_personas = $this->findAll();
        $resultado_nombres = array();
        $resultado_ids = array();

        foreach ($tipos_personas as $tipo_persona) {
            array_push($resultado_nombres, $tipo_persona->getNombre());
            array_push($resultado_ids, $tipo_persona->getId());            
        }  

        return array_combine($resultado_nombres,$resultado_ids);         
    }
}