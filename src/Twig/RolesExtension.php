<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class RolesExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('parsearRol', [$this, 'parsearRol'], ['is_safe' => ['html']]),
        ];
    }

    public function parsearRol(array $roles):string{
        try {
            $roles_parseados = "";
            $roles_disponibles = array_flip(array(
                'Administrador/a' => 'ROLE_ADMINISTRADOR' ,
                'Jurídico/a' => 'ROLE_JURIDICO',
                'Económico/a' => 'ROLE_ECONOMICO',
                'Consultor/a' => 'ROLE_CONSULTOR',
            ));

            foreach($roles as $rol){
                $roles_parseados .= '('.$roles_disponibles[$rol].')';
            }
            return $roles_parseados;
        }catch (\Exception $e){
            return "Sin definir";
        }
    }
}
