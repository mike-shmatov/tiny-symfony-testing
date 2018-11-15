<?php
namespace Tests\TinyTestingBundle;

use RuntimeException;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Tests\TestApp\Bundles\SimpleAppBundle\SimpleAppBundle;
use Tests\TestApp\TempAppKernelCreationTrait;
use Tiny\SymfonyTesting\Test\WebTestCase;
use Tiny\SymfonyTesting\TinyTestingBundle\TinyTestingBundle;

class BundleTest extends WebTestCase
{
    use TempAppKernelCreationTrait;
    
    public static function createClient(array $options = array(), array $server = array()) {
        $client = parent::createClient([
            'config' => __DIR__.'/../TestApp/with-twig-config.yml',
            'bundles' => [
                TwigBundle::class,
                SimpleAppBundle::class,
                TinyTestingBundle::class
            ]
        ]);
        return $client;
    }
    
    public function testHomepageOk() {
        $this->get('/');
        $this->assertStatusCode(200);
    }
    
    public function testNotFoundRenderedThroughTwig() {
        $this->get('/whatever');
        $this->assertStatusCode(404);
    }
    
    public function testNotHttpException() {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('expected');
        $this->get('/echo/throw');
    }
}
