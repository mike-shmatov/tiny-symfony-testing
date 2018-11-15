<?php
namespace Tests\TestApp;

use RuntimeException;
use Tests\TestApp\TestAppKernel;

class TestAppKernelBuilder
{
    private $varDirPath;
    private $config;
    private $bundles = [];
    private $environment = 'test';
    private $debug = true;
    private $class;
    
    public function __construct($kernelClassName = null) {
        if ($kernelClassName) {
            $this->class = $kernelClassName;
        } else {
            $this->class = TestAppKernel::class;
        }
    }
    
    public function setVarDirPath($path) {
        if ( ! is_dir($path)) {
            throw new RuntimeException(sprintf("The path provided (%s) to be parent dir for /var directory does not exist or is not a directory.", $path));
        }
        $this->varDirPath = $path;
        return $this;
    }
    
    public function setConfigPath($path) {
        if ( !file_exists($path)) {
            throw new RuntimeException(sprintf("Provided path for kernel config '%s' does not exist.", $path));
        }
        $this->config = $path;
        return $this;
    }
    
    public function setBundleClasses($bundles) {
        if ( ! is_array($bundles)) $bundles = [$bundles];
        $this->bundles = $bundles;
        return $this;
    }
    
    public function setEnvironment($environment) {
        $this->environment = $environment;
        return $this;
    }
    
    public function setDebug($debug) {
        $this->debug = (boolean) $debug;
        return $this;
    }
    
    public function makeKernelFromOptions(array $options) {
        $configPath = isset($options['config']) ? $options['config'] : '';
        $varPath = isset($options['var']) ? $options['var'] : '';
        $bundles = isset($options['bundles']) ? $options['bundles'] : [];
        $env = isset($options['environment']) ? $options['environment'] : 'test';
        $debug = isset($options['debug']) ? $options['debug'] : true;
        return $this->setConfigPath($configPath)
                    ->setVarDirPath($varPath)
                    ->setBundleClasses($bundles)
                    ->setEnvironment($env)
                    ->setDebug($debug)
                    ->makeKernel();
    }
    
    public function makeKernel() {
        return new 
            $this->class(
                    $this->config, 
                    $this->varDirPath, 
                    $this->bundles, 
                    $this->environment, 
                    $this->debug
            );
    }
}
