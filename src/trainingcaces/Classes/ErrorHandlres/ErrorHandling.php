<?php

namespace T3Dev\Trainingcaces\ErrorHandlres;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Error\PageErrorHandler\PageErrorHandlerInterface;
use TYPO3\CMS\Core\Http\RedirectResponse;

class ErrorHandling implements PageErrorHandlerInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param string $message
     * @param array $reasons
     * @return ResponseInterface
     */
    public function handlePageError(ServerRequestInterface $request, string $message, array $reasons = []): ResponseInterface
    {

        //if (strpos($request->getRequestTarget(), '/page-where-i-want-my-special-404') !== false) {
        //    return new RedirectResponse('/my-custom-404', 404);
        //}

        return new RedirectResponse('api', 404);
    }
}
