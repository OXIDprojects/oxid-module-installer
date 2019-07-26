<?php

/**
 * Contao Open Source CMS
 */

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Extension;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder AS BaseContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension AS BaseExtension;

class Extension extends BaseExtension
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
    }
}
