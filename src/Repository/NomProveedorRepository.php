<?php

namespace App\Repository;

use App\Entity\NomProvincia;
use Doctrine\ORM\EntityRepository;

class NomProveedorRepository extends EntityRepository
{
    public function getParsedFieldFromSelect(): array
    {
        $proveedores = $this->findAll();
        $resultado_nombres = array();
        $resultado_ids = array();
        $provincia = null;

        foreach ($proveedores as $proveedor) {
        	$provincia = $this->getEntityManager()->getRepository(NomProvincia::class)->find($proveedor->getProvincia());
        	array_push($resultado_nombres, $proveedor->getNombre()." - ".$provincia->getNombre());
            array_push($resultado_ids, $proveedor->getId());            
        }  

        return array_combine($resultado_nombres,$resultado_ids);         
    }
}