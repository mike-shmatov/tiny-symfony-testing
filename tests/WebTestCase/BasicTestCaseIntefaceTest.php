<?php
namespace Tests\WebTestCase;

use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestApp\Bundles\SimpleAppBundle\SimpleAppBundle;
use Tests\TestApp\TempAppKernelCreationTrait;
use Tiny\SymfonyTesting\Test\WebTestCase;

class BasicTestCaseIntefaceTest extends WebTestCase
{
    use TempAppKernelCreationTrait;
    
    protected static function createClient(array $options = [], array $server = []) {
        $client = parent::createClient([
            'config' => realpath(__DIR__.'/../TestApp/simple-app-bundle-config.yml'),
            'bundles' => [SimpleAppBundle::class]
        ]);
        return $client;
    }
    
    public function testRequestAndResponseCanBeAccessed() {
        $this->request('/');
        $this->assertInstanceOf(Response::class, $this->response);
    }
    
    public function testThrowsWhenAccessingResponseWithoutRequest() {
        $this->expectException(LogicException::class);
        $this->response;
    }
    
    public function testStatusCodeAssertion() {
        $this->request('/');
        $this->assertStatusCode(200);
        $this->assertEquals(200, $this->response->getStatusCode());
    }
    
    public function testCrawlerCanBeUsed() {
        $this->request('/html');
        $this->assertContains('Success!', $this->crawler->filter('h1')->text());
    }
    
    public function testSetHost() {
        $this->setHost('subdomain.domain');
        $this->get('/echo/request');
        $this->assertEquals('subdomain.domain', json_decode($this->response->getContent())->server->HTTP_HOST);
    }
    
    public function testSetHeader() {
        $this->setHeader('Authorization', 'whatever');
        $this->get('/echo/request');
        $this->assertEquals('whatever', json_decode($this->response->getContent())->server->HTTP_AUTHORIZATION);
        $this->assertEquals('whatever', json_decode($this->response->getContent())->headers->authorization[0]);
    }
    
    public function testSetCookie() {
        $this->setCookie('name', 'value');
        $this->get('/echo/request');
        $this->assertEquals('value', json_decode($this->response->getContent())->cookies->name);
    }
    
    public function testSetParameters() {
        $this->setParameters(['name' => 'value']);
        $this->post('/echo/request');
        $this->assertEquals('value', json_decode($this->response->getContent())->post->name);
    }
    
    public function testSetParametersWorksForGetMethod() {
        $this->setParameters(['name' => 'value']);
        $this->get('/echo/request');
        $this->assertEquals('value', json_decode($this->response->getContent())->get->name);
    }
    
    public function testParametersCleanedAfterRequest() {
        $this->setParameters(['name' => 'value']);
        $this->post('/echo/request');
        $this->post('/echo/request');
        $this->assertCount(0, json_decode($this->response->getContent())->post);
    }
}
