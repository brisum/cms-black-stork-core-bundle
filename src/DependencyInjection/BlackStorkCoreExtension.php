<?php

namespace BlackStork\Core\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

class BlackStorkCoreExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('black_stork_core.seo.templates',  $config['seo']['templates']);
        $container->setParameter('black_stork_core.page.templates', $config['page']['templates']);
        $container->setParameter('black_stork_core.page.statuses',  $config['page']['statuses']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('command.yaml');
        $loader->load('sonata_admin.yaml');
    }
}
