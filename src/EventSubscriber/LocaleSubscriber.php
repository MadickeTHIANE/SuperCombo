<?php
// src/EventSubscriber/LocaleSubscriber.php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct(string $defaultLocale = 'fr')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $request->getSession()->start();
        $path = $request->getPathInfo();

        if (!$request->hasPreviousSession()) {
            return;
        }
        
        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
  
        if ($path == '/') {
            if( $request->getPreferredLanguage()){
                $countryLanguage = $request->getPreferredLanguage();
                $locale = substr($countryLanguage,0,2);
            } else {
            $locale = $defaultLocale;
        }
            $url = $request->getSchemeAndHttpHost().'/'.$locale;
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }
        
    // redirect with locale into route

    public static function getSubscribedEvents()
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 64]],
        ];
    }
}

