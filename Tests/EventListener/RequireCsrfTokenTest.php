<?php

use Doctrine\Common\Annotations\Reader;
use MakG\SymfonyUtilsBundle\Annotation\CsrfTokenRequired;
use MakG\SymfonyUtilsBundle\EventListener\RequireCsrfToken;
use MakG\SymfonyUtilsBundle\Tests\TestController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class RequireCsrfTokenTest extends TestCase
{
    public function testOnKernelRequest()
    {
        $validToken = 'csrf-token';
        $tokenInRequest = $validToken;
        $annotation = new CsrfTokenRequired(['id' => 'api']);

        $reader = $this->createMock(Reader::class);
        $reader
            ->expects($this->atLeastOnce())
            ->method('getMethodAnnotation')
            ->willReturn($annotation);

        $csrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfTokenManager
            ->expects($this->atLeastOnce())
            ->method('isTokenValid')
            ->willReturnCallback(
                function (CsrfToken $csrfToken) use ($validToken) {
                    return $csrfToken->getValue() === $validToken;
                }
            );

        $request = $this->createMock(Request::class);
        $request
            ->method('get')
            ->with($annotation->param)
            ->willReturnCallback(
                function () use (&$tokenInRequest) {
                    return $tokenInRequest;
                }
            );
        $request->headers = new ParameterBag(
            [
                'X-CSRF-Token' => null,
            ]
        );

        $event = $this->createMock(FilterControllerEvent::class);
        $event
            ->expects($this->atLeastOnce())
            ->method('getController')
            ->willReturn([TestController::class, 'action']);
        $event
            ->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        // Valid token - no exception
        $listener = new RequireCsrfToken($reader, $csrfTokenManager);
        $listener->onKernelController($event);

        // Invalid token
        $tokenInRequest = 'invalid-token';

        $this->expectException(BadRequestHttpException::class);

        $listener->onKernelController($event);
    }
}
