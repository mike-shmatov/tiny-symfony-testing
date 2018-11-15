<?php
namespace Tiny\SymfonyTesting\Test;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property-read Response $response Symfony http-foundation Response instance
 */
class WebTestCase extends BaseTestCase
{
    /**
     * @var Client
     */
    protected $client;
    
    /**
     * @var Crawler
     */
    protected $crawler;
    
    private $data = [
        'server' => [],
        'cookies' => [],
        'parameters' => [],
        'headers' => []
    ];
    
    public function setUp() {
        $this->client = static::createClient();
    }
    
    protected function request($uri, $method = 'GET', $parameters = [], $files = [], $server = [], $content = '', $changeHistory = true) {
        $server = array_merge($server, $this->data['server']);
        $this->client->setServerParameters($server);
        foreach ($this->data['cookies'] as $cookie) {
            $this->client->getCookieJar()->set($cookie);
        }
        $parameters = array_merge($this->data['parameters'], $parameters);
        $this->crawler = $this->client->request($method, $uri, $parameters, $files, $server, $content, $changeHistory);
        $this->response = $this->client->getResponse();
        $this->data['parameters'] = [];
    }
    
    protected function setHost($host) {
        $this->data['server']['HTTP_HOST'] = $host;
    }
    
    protected function setHeader($name, $value) {
        $name = strtoupper(str_replace('-', '_', $name));
        $this->data['server']['HTTP_'.$name] = $value;
    }
    
    protected function setCookie($name, $value, $expires = null, $path = null, $domain = '', $secure = false, $httponly = false) {
        $cookie = new Cookie($name, $value, $expires, $path, $domain, $secure, $httponly);
        $this->data['cookies'][] = $cookie;
    }
    
    protected function setParameters(array $parameters) {
        $this->data['parameters'] = $parameters;
    }
    
    protected function assertStatusCode($expected) {
        $this->assertEquals($expected, $this->response->getStatusCode());
    }
    
    protected function get($uri, $parameters = []) {
        $this->request($uri, 'GET', $parameters);
    }
    
    protected function post($uri, $content = '', $parameters = []) {
        $this->request($uri, 'POST', $parameters, [], [], $content);
    }
    
    protected function put($uri, $content = '') {
        $this->request($uri, 'PUT', [], [], [], $content);
    }
    
    protected function patch($uri, $content = '') {
        $this->request($uri, 'PATCH', [], [], [], $content);
    }
    
    protected function delete($uri, $content = '') {
        $this->request($uri, 'DELETE', [], [], [], $content);
    }
    
    public function __get($name) {
        if ($name === 'response') {
            if ( ! isset($this->response)) {
                throw new LogicException('No response. Probably you are trying to access response while request was not made yet.');
            }
            return $this->response;
        }
    }
}
