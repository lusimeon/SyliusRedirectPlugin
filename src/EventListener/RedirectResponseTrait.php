<?php

declare(strict_types=1);

namespace Setono\SyliusRedirectPlugin\EventListener;

use League\Uri\Uri;
use League\Uri\UriModifier;
use Setono\SyliusRedirectPlugin\Model\RedirectInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
trait RedirectResponseTrait
{
    public static function getRedirectResponse(RedirectInterface $lastRedirect, string $queryString = null, ?string $currentPath = null): RedirectResponse
    {
        $uri = Uri::createFromString((string) $lastRedirect->getFinalDestination($currentPath));

        if ($lastRedirect->keepQueryString() && null !== $queryString) {
            $uri = UriModifier::appendQuery($uri, $queryString);
        }

        return new RedirectResponse(
            $uri->__toString(),
            $lastRedirect->isPermanent() ? Response::HTTP_MOVED_PERMANENTLY : Response::HTTP_FOUND
        );
    }
}
