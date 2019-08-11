<?php

namespace MakG\SymfonyUtilsBundle\EventListener;


use Doctrine\Common\Annotations\Reader;
use MakG\SymfonyUtilsBundle\Annotation\CsrfTokenRequired;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Handles @CsrfTokenRequired annotation.
 */
class RequireCsrfToken implements EventSubscriberInterface
{
    private $reader;
    private $csrfTokenManager;

    public function __construct(Reader $reader, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->reader = $reader;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(FilterControllerEvent $event): void
    {
        list($controller, $action) = $event->getController();

        $method = new \ReflectionMethod($controller, $action);
        $annotation = $this->reader->getMethodAnnotation($method, CsrfTokenRequired::class);

        if (!$annotation instanceof CsrfTokenRequired) {
            return;
        }


        $request = $event->getRequest();
        $token = null;

        if ($annotation->header) {
            $token = $request->headers->get($annotation->header);
        }

        if (null === $token && $annotation->param) {
            $token = $request->get($annotation->param);
        }

        $csrfToken = new CsrfToken($annotation->id, $token);

        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            throw new BadRequestHttpException('Invalid token.');
        }
    }
}
