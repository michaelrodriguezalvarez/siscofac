<?php
namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\User;
use App\Twig\ConfNotificacionExtension;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    protected $doctrine;
    protected $encoder;

    public function __construct(RegistryInterface $doctrine, UserPasswordEncoderInterface $encoder)
    {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof User) {
            $entity = $entityManager->getRepository(User::class)
                ->encodePassword($this->encoder, $entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof User) {
            $entity = $entityManager->getRepository(User::class)
                ->encodePassword($this->encoder, $entity);
        }
    }
}