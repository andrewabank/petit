<?php

namespace Acme\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tikit\TikitBundle\Service\PetitionModel;
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
    public $securityContext;

    public function __construct(ContainerInterface $container, $entityManager)
    {
        $securityContext = $container->get('security.context');
        $this->securityContext = $securityContext;
        $this->em = $entityManager;
        $someService = new PetitionModel($this->em);
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
        $user = $this->securityContext->getToken()->getUser();
        //$this->someService->postUserApprovedVote($user);
    }
}

