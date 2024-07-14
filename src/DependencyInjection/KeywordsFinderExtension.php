<?php

namespace KeywordsFinder\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Will inject services.yaml configuration from this package to the parent tool
 */
class KeywordsFinderExtension extends Extension
{

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . "/../../config")
        );

        $env = $container->getParameter("kernel.environment");

        $loader->load('services.yaml');
        $loader->load("packages/{$env}/params.yaml");
    }
}