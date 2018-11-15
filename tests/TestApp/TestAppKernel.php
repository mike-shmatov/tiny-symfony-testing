<?php
namespace Tests\TestApp;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestAppKernel extends Kernel
{
    private $configPath;
    private $varDirectoryPath;
    private $extraBundles;
    
    public function __construct($configPath, $varPath, $bundles = [], $environment = 'test', $debug = true) {
        $this->configPath = $configPath;
        $this->varDirectoryPath = $varPath;
        $this->extraBundles = $bundles;
        parent::__construct($environment, $debug);
    }
    
    public function registerBundles() {
        $bundles = [new FrameworkBundle()];
        foreach ($this->extraBundles as $bundleClass) {
            array_push($bundles, new $bundleClass());
        }
        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader) {
        $loader->load($this->configPath);
    }
    
    public function getCacheDir() {
        return $this->varDirectoryPath.'/cache/'.$this->environment;
    }
    
    public function getLogDir() {
        return $this->varDirectoryPath.'/logs';
    }
    
    public function getProjectDir() {
        return __DIR__;
    }
    
    public function getContainerClass() {
        $className = parent::getContainerClass().substr(md5($this->configPath), -8);
        return $className;
    }
}
