<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class NomTipoServicioRepository extends EntityRepository
{
    public function getParsedFieldFromSelect(): array
    {
        $tipos_servicios = $this->findAll();
        $resultado_nombres = array();
        $resultado_ids = array();

        foreach ($tipos_servicios as $tipo_servicio) {
            array_push($resultado_nombres, $tipo_servicio->getNombre());
            array_push($resultado_ids, $tipo_servicio->getId());            
        }  

        return array_combine($resultado_nombres,$resultado_ids);         
    }
}