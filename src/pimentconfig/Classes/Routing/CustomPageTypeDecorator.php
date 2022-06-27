<?php

namespace Piment\PimentConfig\Routing;

use TYPO3\CMS\Core\Routing\Enhancer\PageTypeDecorator;
use TYPO3\CMS\Core\Routing\RouteCollection;

/**
 * CustomPageTypeDecorator
 */
class CustomPageTypeDecorator extends PageTypeDecorator
{
    public const IGNORE_INDEX = [
        '/index.html',
        '/index/',
    ];
    public const ROUTE_PATH_DELIMITERS = ['.', '-', '_', '/'];
    /**
     * @param \TYPO3\CMS\Core\Routing\RouteCollection $collection
     * @param array $parameters
     */
    public function decorateForGeneration(RouteCollection $collection, array $parameters): void
    {
        parent::decorateForGeneration($collection, $parameters);
        /**
         * @var string $routeName
         * @var \TYPO3\CMS\Core\Routing\Route $route
         */
        foreach ($collection->all() as $routeName => $route) {
            $path = $route->getPath();
            if (true === \in_array($path, self::IGNORE_INDEX, true)) {
                $route->setPath('/');
            }
        }
    }
}
