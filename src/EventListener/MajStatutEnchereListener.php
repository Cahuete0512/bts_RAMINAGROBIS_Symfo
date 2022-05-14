<?php
namespace App\EventListener;

use App\Entity\Enchere;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class MajStatutEnchereListener
{
    private $logger;
    private $doctrine;

    public function __construct(LoggerInterface $logger,
                                ManagerRegistry $doctrine)
    {
        $this->logger = $logger;
        $this->doctrine = $doctrine;
    }
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postPersist(Enchere $enchere, LifecycleEventArgs $event): void
    {
        $this->logger->debug("PostPersist enchere " . $enchere->getId());
        $enchereRepo = $this->doctrine->getRepository(Enchere::class);
        $enchereRepo->updateStatut();
        $this->logger->debug("Fin du PostPersist enchere ");
    }
}