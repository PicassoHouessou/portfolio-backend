<?php

namespace App\EventSubscriber;

use App\Repository\LocaleRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Symfony\Component\String\u;

class LocaleListener implements EventSubscriberInterface
{

    private $locales;
    private $defaultLocale;
    //private $translatableListener;
    protected $currentLocale;

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator,
                                LocaleRepository                       $localeRepository)
    {
        //$this->translatableListener = $translatableListener;
        $this->locales = $localeRepository->getAvailableLocales();
        $this->defaultLocale = $localeRepository->getDefaultLocale();
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 200)),
            KernelEvents::RESPONSE => array('setContentLanguage')
        );
    }

    public function onKernelRequest(RequestEvent $event)
    {
        // Persist DefaultLocale in translation table
        //$this->translatableListener->setPersistDefaultLocaleTranslation(true);

        /** @var Request $request */
        $request = $event->getRequest();
        if ($request->headers->has("X-LOCALE")) {
            $locale = $request->headers->get('X-LOCALE');
            if (in_array($locale, $this->locales)) {
                $request->setLocale($locale);
            } else {
                $request->setLocale($this->defaultLocale);
            }
        } else {
            $request->setLocale($this->defaultLocale);
        }
        // Set currentLocale
        //$this->translatableListener->setTranslatableLocale($request->getLocale());
        $this->currentLocale = $request->getLocale();

        // Ignore sub-requests and all URLs but the homepage
        if (!$event->isMainRequest() || '/' !== $request->getPathInfo()) {
            return;
        }
        // Ignore requests from referrers with the same HTTP host in order to prevent
        // changing language for users who possibly already selected it for this application.
        $referrer = $request->headers->get('referer');
        if (null !== $referrer && u($referrer)->ignoreCase()->startsWith($request->getSchemeAndHttpHost())) {
            return;
        }

        $preferredLanguage = $request->getPreferredLanguage($this->locales);

        if ($preferredLanguage !== $this->defaultLocale) {
            $response = new RedirectResponse($this->urlGenerator->generate('homepage', ['_locale' => $preferredLanguage]));
            $event->setResponse($response);
        }
    }

    public function setContentLanguage(ResponseEvent $event): Response
    {
        $response = $event->getResponse();
        $response->headers->add(array('Content-Language' => $this->currentLocale));

        return $response;
    }
}