<?php

namespace OxidCommunity\ModuleInstaller;

use OxidCommunity\ModuleInstaller\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle AS BaseBundle;
use OxidCommunity\SymfonyKernel\Bundle\BundleRoutesInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;
use OxidCommunity\SymfonyKernel\DependencyInjection\ContainerBuilder;
use OxidCommunity\SymfonyKernel\Bundle\BundleConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use OxidCommunity\SymfonyKernel\DependencyInjection\Loader\YamlFileLoader;

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
        $extensionConfigs = $container->getExtensionConfigs();

        if ($name === 'twig') {
            if(empty($extensionConfigs['twig']['paths'])) {
                $extensionConfigs['twig']['paths'] = [];
            }
            if(is_dir($container->getParameter('kernel.root_dir').'/../vendor/oxid-community/oxid-module-installer/src/Resources/views/')) {
                $extensionConfigs['twig']['paths'][$container->getParameter('kernel.root_dir').'/../vendor/oxid-community/oxid-module-installer/src/Resources/views/'] = 'OxidCommunityModuleInstaller';
            } else {
                $extensionConfigs['twig']['paths'][$container->getParameter('kernel.project_dir').'/src/Resources/views/'] = 'OxidCommunityModuleInstaller';
            }
        }
        
        return $extensionConfigs;
    }

}
