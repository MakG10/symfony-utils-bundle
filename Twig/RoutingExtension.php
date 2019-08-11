<?php

namespace MakG\SymfonyUtilsBundle\Twig;


use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RoutingExtension extends AbstractExtension
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('path_js', [$this, 'pathJs']),
        ];
    }

    /**
     * Generates path for route suitable for JavaScript code (with parameter placeholders)
     *
     * @param string $routeName Name of the route to generate path for
     * @return string
     * @throws RouteNotFoundException
     */
    public function pathJs(string $routeName): string
    {
        $route = $this->router->getRouteCollection()->get($routeName);

        if (null === $route) {
            throw new RouteNotFoundException(sprintf('Route with name "%s" was not found.', $routeName));
        }


        return $route->getPath();
    }
}
