<?php

namespace App\Repository;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function encodePassword(UserPasswordEncoderInterface $encoder, User $user):User {
    	 $encoded = $encoder->encodePassword($user, $user->getPassword());
    	 $user->setPassword($encoded);
    	 return $user;
    }

    public function getCantidadUsuariosAdministradores():int{
        $consulta = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(u.id) AS Cantidad')
            ->from('App\Entity\User','u')
            ->where('u.roles LIKE \'%ROLE_ADMINISTRADOR%\'');
        return $this->getEntityManager()->createQuery($consulta->getDQL())
                    ->getArrayResult()[0]['Cantidad'];
    }
}