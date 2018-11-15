<?php
namespace Tests\AppBundle;

use Tests\TestApp\Bundles\SimpleAppBundle\SimpleAppBundle;
use Tests\TestApp\TempAppKernelCreationTrait;
use Tiny\SymfonyTesting\Test\WebTestCase;

class VerbsShortcutsTest extends WebTestCase
{
    use TempAppKernelCreationTrait;
    
    protected static function createClient(array $options = [], array $server = []) {
        $client = parent::createClient([
            'config' => realpath(__DIR__.'/../TestApp/simple-app-bundle-config.yml'),
            'bundles' => [SimpleAppBundle::class]
        ]);
        return $client;
    }
    
    public function testGetVerb() {
        $this->get('/');
        $this->assertStatusCode(200);
    }
    
    public function testPostVerb() {
        $this->post('/');
        $this->assertStatusCode(200);
        $this->assertEquals('post ok', $this->response->getContent());
    }
    
    public function testPutVerb() {
        $this->put('/');
        $this->assertStatusCode(200);
        $this->assertEquals('put ok', $this->response->getContent());
    }
    
    public function testPatchVerb() {
        $this->patch('/');
        $this->assertStatusCode(200);
        $this->assertEquals('patch ok', $this->response->getContent());
    }
    
    public function testDeleteVerb() {
        $this->delete('/');
        $this->assertStatusCode(200);
        $this->assertEquals('delete ok', $this->response->getContent());
    }
    
    public function testGetParameters() {
        $this->get('/echo/request', ['key' => 'value']);
        $this->assertEquals(['key' => 'value'], json_decode($this->response->getContent(), true)['get']);
    }
    
    public function testPostParameters() {
        $this->post('/echo/request', '', ['key' => 'value']);
        $this->assertEquals(['key' => 'value'], json_decode($this->response->getContent(), true)['post']);
    }
    
    public function testPostContentable() {
        $this->post('/echo/request', 'body');
        $this->assertEquals('body', json_decode($this->response->getContent())->content);
    }
    
    public function testPutContentable() {
        $this->put('/echo/request', 'body');
        $this->assertEquals('body', json_decode($this->response->getContent())->content);
    }
    
    public function testPatchContentable() {
        $this->patch('/echo/request', 'body');
        $this->assertEquals('body', json_decode($this->response->getContent())->content);
    }
    
    public function testDeleteContentable() {
        $this->delete('/echo/request', 'body');
        $this->assertEquals('body', json_decode($this->response->getContent())->content);
    }
}
