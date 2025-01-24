<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

class LocaleListener implements EventSubscriberInterface
{
    private string $defaultLocale;

    private string $systemLocale;

    /** @var array<string> */
    private array $locales;

    public function __construct(/* string $defaultLocale, string $systemLocale, array $locales */)
    {
        $this->defaultLocale = 'es';
        $this->systemLocale = 'es';
        $this->locales = ['es', 'en'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $requestUri = $request->getRequestUri();

        if (!$event->isMainRequest()) {
            return;
        }

        /** @var Session $session */
        $session = $request->getSession();

        /** @var string $newLocale */
        $newLocale = $this->defaultLocale;

        if (substr($requestUri, 0, 10) == '/_profiler') {
            return;
        }

        if (substr($requestUri, 0, 6) == '/admin') {
            $newLocale = $this->systemLocale;
            $newLocaleBy = 'admin';
        } else {
            /** @var string $newLocaleBy */
            $newLocaleBy = 'none';

            /** @var string $paramLocale */
            $paramLocale = $request->get('locale');

            /** @var ?string $sessionLocale */
            $sessionLocale = null;

            if ($session != null) {
                /** @var string $slocale */
                $slocale = $session->get('locale');
                $sessionLocale = (string) $slocale;
            }

            if ($paramLocale && in_array($paramLocale, $this->locales)) {
                $newLocale = $paramLocale;
                $newLocaleBy = 'param';
            } elseif (!empty($sessionLocale) && in_array($sessionLocale, $this->locales)) {
                $newLocale = $sessionLocale;
                $newLocaleBy = 'session';
            } else {
                $userDefaultLocale = $this->defaultLocale;
                if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                    $langs = [];
                    preg_match_all(
                        '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
                        $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                        $lang_parse
                    );
                    if (count($lang_parse[1]) > 0) {
                        $langs = array_combine($lang_parse[1], $lang_parse[4]);
                        foreach ($langs as $lang => $val) {
                            if ($val === '') {
                                $langs[$lang] = 1;
                            }
                        }
                        arsort($langs, SORT_NUMERIC);
                    }
                    foreach (array_keys($langs) as $lang) {
                        if (in_array($lang, $this->locales)) {
                            $userDefaultLocale = $lang;
                            break;
                        }
                    }
                }
                $newLocale = $userDefaultLocale;
                $newLocaleBy = 'user';
            }

            /** @var string $routeName */
            $routeName = $request->attributes->get('_route');

            if (!empty($routeName) && strpos($routeName, '_') !== false) {
                /** @var array<string> $routeLang */
                $routeLang = explode('_', $routeName);
                if (count($routeLang) >= 2) {
                    $routeLang = $routeLang[count($routeLang) - 1];
                    if (in_array($routeLang, $this->locales)) {
                        $newLocale = $routeLang;
                        $newLocaleBy = 'route';
                    }
                }
            }

            $request->setLocale($newLocale);

            if ($session != null) {
                $session->set('locale', $newLocale);
            }

            // echo '$newLocale: ' . $newLocale . '<br/>';
            // echo '$newLocaleBy: ' . $newLocaleBy . '<br/>';
        }
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {
            $this->onKernelRequest($event);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 30]],
            KernelEvents::EXCEPTION => ['onKernelException', -64],
        ];
    }
}
