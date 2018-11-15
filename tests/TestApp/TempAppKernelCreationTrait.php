<?php
namespace Tests\TestApp;

use org\bovigo\vfs\vfsStream;

trait TempAppKernelCreationTrait
{
    private static $builder;
    private static $varDir;
    
    protected static function createVarDirectory($root = 'root') {
        vfsStream::setup($root, null, [
            'var' => []
        ]);
        return vfsStream::url($root.'/var');
    }
    
    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$builder = new TestAppKernelBuilder();
        self::$varDir = self::createVarDirectory('root');
    }
    
    protected static function removeTempVarDirectory() {
        // no op
    }
    
    public static function createKernel(array $options = array()) {
        $options['var'] = self::$varDir;
        return self::$builder->makeKernelFromOptions($options);
    }
    
    public static function tearDownAfterClass() {
        parent::tearDownAfterClass();
        self::removeTempVarDirectory();
    }
}
