<?php
namespace Tests\Environment;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestApp\Bundles\SimpleAppBundle\SimpleAppBundle;
use Tests\TestApp\TempAppKernelCreationTrait;
use Tiny\SymfonyTesting\Test\WebTestCase;

class KernelBootabilityTest extends WebTestCase
{
    use TempAppKernelCreationTrait;
    
    public function setUp() {
        // do nothing so client does not get created
        // in AppBundle\WebTestCase::setUp()
    }
    
    public function testKernelBoots() {
        $client = self::createClient([
            'config' => __DIR__.'/../TestApp/no-bundles-config.yml'
        ]);
        $this->expectException(NotFoundHttpException::class);
        $client->request('GET', '/');
    }
    
    public function testKernelBootsWithExtraBundle() {
        $client = self::createClient([
            'config' => realpath(__DIR__.'/../TestApp/simple-app-bundle-config.yml'),
            'bundles' => [SimpleAppBundle::class]
        ]);
        $client->request('GET', '/');
        $this->assertEquals('get ok', $client->getResponse()->getContent());
    }
}
