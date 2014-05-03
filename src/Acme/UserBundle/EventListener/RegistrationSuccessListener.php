<?php

namespace Acme\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Tikit\TikitBundle\Service\TikitModel as TikitModel;
/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class RegistrationSuccessListener implements EventSubscriberInterface
{
    /*private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }*/

    /**
     * {@inheritDoc}
     */
    private $someService;
    private $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
        $someService = new TikitModel($this->em);
        $this->someService = $someService;
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess()
    {
        //$this->someService->voteTikit(1,1,1);
    }
}

