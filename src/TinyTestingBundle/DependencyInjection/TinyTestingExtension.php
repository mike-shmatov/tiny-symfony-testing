<?php
namespace Tiny\SymfonyTesting\TinyTestingBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;


class TinyTestingExtension extends \Symfony\Component\DependencyInjection\Extension\Extension
{
    public function load(array $configs, ContainerBuilder $container) {
        $locator = new FileLocator(realpath(__DIR__.'/../Resources/config'));
        $loader = new XmlFileLoader($container, $locator);
        $loader->load('services.xml');
    }
}
