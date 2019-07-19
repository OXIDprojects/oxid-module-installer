<?php

/**
 * Contao Open Source CMS
 */

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Extension;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Sioweb\Oxid\Kernel\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder AS BaseContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension AS BaseExtension;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;

class Extension extends BaseExtension implements PrependExtensionInterface //implements OxidKernelExtensionInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getAlias()
	{
		return 'oxidcommunity-moduleinstaller';
    }
  
    public function load(array $configs, BaseContainerBuilder $container)
    {
        // Late loading
    }

    public function prepend(BaseContainerBuilder $container)
    {
        // Preloading
    }
}
