<?php

namespace Brisum\Stork\Bundle\CoreBundle\TwigBundle;

use Brisum\Stork\Bundle\CoreBundle\TwigBundle\Event\PreRenderEvent;
use Symfony\Bundle\TwigBundle\TwigEngine as BaseTwigEngine;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class TwigEngine extends BaseTwigEngine implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     */
    public function renderResponse($view, array $parameters = array(), Response $response = null)
    {
        $event = new PreRenderEvent($view, $parameters, $response, $this->getRequest());

        $this->getEventDispatcher()->dispatch(TwigEngineEvents::PRE_RENDER, $event);

        return parent::renderResponse($event->getView(), $event->getParameters(), $event->getResponse());
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }
}
