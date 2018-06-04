<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ContratoRepository extends EntityRepository
{
    public function getUltimosNAnnosHastaActual($n): array
    {
        $anno_actual = date('Y');
        $annos = array();

        while ($n >= 0) {
            array_unshift($annos, $anno_actual - $n);
            $n--;
        }

        return array_combine($annos, $annos);
    }

}