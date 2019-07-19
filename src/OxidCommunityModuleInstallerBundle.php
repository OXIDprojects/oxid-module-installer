<?php

namespace OxidCommunity\ModuleInstaller;

use OxidCommunity\ModuleInstaller\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle AS BaseBundle;
use Sioweb\Oxid\Kernel\Bundle\BundleRoutesInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;
use Sioweb\Oxid\Kernel\DependencyInjection\ContainerBuilder;
use Sioweb\Oxid\Kernel\Bundle\BundleConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Sioweb\Oxid\Kernel\DependencyInjection\Loader\YamlFileLoader;

class OxidCommunityModuleInstallerBundle extends BaseBundle implements BundleRoutesInterface, BundleConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new Extension();
    }
    
    /**
     * Returns a collection of routes for this bundle.
     *
     * @return RouteCollection|null
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver
            ->resolve(__DIR__ . '/Resources/config/routing.yml')
            ->load(__DIR__ . '/Resources/config/routing.yml')
        ;
    }

    public function getBundleConfiguration($name, ContainerBuilder $container) : array
    {
        // $loader = new YamlFileLoader(
        //     $container,
        //     new FileLocator(__DIR__.'/Resources/config')
        // );
        // $loader->load('security.yml');
        // $loader->containerConfig();
        
        return $container->getExtensionConfigs();
    }
}
