<?php

namespace SymEdit\Bundle\CacheBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SymEditCacheExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('voters.xml');

        if ($config['sylius']) {
            $loader->load('sylius.xml');
        }

        $this->setupVoters($container, $config['voters']);
    }

    protected function setupVoters(ContainerBuilder $container, $config)
    {
        // Set the roles
        $container->getDefinition('symedit_cache.voter.role')->replaceArgument(1, $config['roles']);
    }

    public function getAlias()
    {
        return 'symedit_cache';
    }
}
