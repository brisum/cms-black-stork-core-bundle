<?php

namespace BlackStork\Core\Listener;

use BlackStork\Core\TwigBundle\Event\PreRenderEvent;
use BlackStork\Core\TwigBundle\TwigEngineEvents;
use BlackStork\Core\Entity\SeoDataEntity;
use BlackStork\Core\Entity\SeoTemplate;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig_Environment;

class SeoListener implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $routes;

    /**
     * SeoListener constructor.
     * @param Twig_Environment $twig
     * @param array $templates
     */
    public function __construct(EntityManager $entityManager, Twig_Environment $twig, array $templates)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;

        foreach ($templates as $template => $routes) {
            foreach ($routes as $route) {
                $this->routes[$route] = $template;
            }
        }
    }

    public function onPreRender(PreRenderEvent $event)
    {
        $route = $event->getRequest()->get('_route');
        if (!isset($this->routes[$route])) {
            return;
        }

        $parameters = &$event->getParameters();
        $seoData = [
            'title' => null,
            'metaDescription' => null,
            'metaKeywords' => null,
            'breadcrumbs' => null,
            'h1' => null,
            'content' => null
        ];

        if (isset($parameters['seoData']) && is_array($parameters['seoData'])) {
            foreach ($parameters['seoData'] as $name => $value) {
                if (array_key_exists($name, $seoData)) {
                    $seoData[$name] = $value;
                }
            }
        }
        if ($this->isFullSeoData($seoData)) {
            $seoData = $this->templating($seoData, $parameters);
            $parameters['seoData'] = $seoData;
            return;
        }

        if (isset($parameters['entity']) && method_exists($parameters['entity'], 'getSeoData')) {
            /** @var SeoDataEntity $entitySeoData */
            $entitySeoData = $parameters['entity']->getSeoData();
            if ($seoDataTitle = $entitySeoData->getTitle()) {
                $seoData['title'] = $seoDataTitle;
            }
            if ($seoDataMetaDescription = $entitySeoData->getMetaDescription()) {
                $seoData['metaDescription'] = $seoDataMetaDescription;
            }
            if ($seoDataMetaKeywords = $entitySeoData->getMetaKeywords()) {
                $seoData['metaKeywords'] = $seoDataMetaKeywords;
            }
        }
        if ($this->isFullSeoData($seoData)) {
            $seoData = $this->templating($seoData, $parameters);
            $parameters['seoData'] = $seoData;
            return;
        }

        /** @var SeoTemplate $seoTemplate */
        $seoTemplate = $this->entityManager->getRepository(SeoTemplate::class)
            ->findOneBy(['template' => $this->routes[$route]]);
        if ($seoTemplate) {
            null === $seoData['title']           && $seoData['title'] = $seoTemplate->getTitle();
            null === $seoData['metaDescription'] && $seoData['metaDescription'] = $seoTemplate->getMetaDescription();
            null === $seoData['metaKeywords']    && $seoData['metaKeywords'] = $seoTemplate->getMetaKeywords();
            null === $seoData['breadcrumbs']     && $seoData['breadcrumbs'] = $seoTemplate->getBreadcrumbs();
            null === $seoData['h1']              && $seoData['h1'] = $seoTemplate->getH1();
            null === $seoData['content']         && $seoData['content'] = $seoTemplate->getContent();
        }

        $seoData = $this->templating($seoData, $parameters);
        $parameters['seoData'] = $seoData;
        return;
    }

    /**
     * @param array $seoData
     * @return bool
     */
    protected function isFullSeoData(array $seoData)
    {
        foreach ($seoData as $value) {
            if (null === $value) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $seoData
     * @param array $parameters
     * @return array
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    protected function templating(array $seoData, array $parameters)
    {
        foreach ($seoData as $name => $template) {
            if (!$template) {
                continue;
            }
            $seoData[$name] = $this->twig->createTemplate($template)->render($parameters);
        }

        if ($seoData['breadcrumbs']) {
            $seoData['breadcrumbs'] = json_decode($seoData['breadcrumbs'], true);
        }

        return $seoData;
    }

    public static function getSubscribedEvents()
    {
        return [
            TwigEngineEvents::PRE_RENDER => 'onPreRender'
        ];
    }
}
