# tiny/symfony-testing

Assistive package for doing functional testing on web applications built with Symfony framework.

Contains two major components:

- TinyTestingBundle. Aims to deal with displaying exceptions while running functional tests.
- WebTestCase. Built on top of Symfony's WebTestCase to provide more testing-friendly interface.

Currently only Symfony 3.4 is supported.

## TinyTestingBundle

Bundle enables displaying actual exceptions thrown when running functional tests on Symfony application. It allows all `Symfony\Component\HttpKernel\Exception\HttpException`-s to be processed in a normal way (e.g. for twig can render a 404 page), and will rethrow any other exception so it can be caught by PHPUnit and reported.

If you make html web app, then while browsing it in `dev` mode and when something bad happens, you see a nice page with all the information about the exception (its class, code, message, stack trace, etc.). When runnning tests it is more valueable to have this exception actually thrown, so it can be reported to the console (or whatever output you or your IDE use).

Of course, there is an option to do 

```php
$client->catchExceptions(false);
```

in the test case. This will force `HttpKernel` throw exception instead of dispatching `kernel.exception` event.

The problem is that using this option requires some manual work (not much but on a regular basis). We have to either turn catching off each time we want to see an exception (and rerun the tests) or take care of creating (or configuring) different clients to support both cases.

Most likely, bundle should be registered only for `test` environment:

```php
// AppKernel.php
if ($this->getEnvironment() === 'test') {
    $bundles[] = new Tiny\SymfonyTesting\TinyTestingBundle\TinyTestingBundle();
}
```

## WebTestCase

Provides some assistive interface for functional testing. Extends Symfony's native `Symfony\Bundle\FrameworkBundle\Test\WebTestCase`.

Its `setUp()` creates instance of a client (can be access directly via `$this->client`, it is a protected). So if you have your own `setUp()` make sure to call `parent::setUp()`.

##### Some configurative (i.e. to be used before request) methods, they have impact on all subsequent requests.

`protected function setHost($host)`

sets the host, useful when request routing involves host requirements (like `api.example.com/users`)

`protected function setHeader($name, $value)`

so stuff like `$this->setHeader('Authoriation', 'Bearer wh.at.ever');` can be done

`protected function setCookie($name, $value, $expires = null, $path = null, $domain = '', $secure = false, $httponly = false)`

##### Provided request methods:

`protected request($uri, $method = 'GET', $parameters = [], $files = [], $server = [], $content = '', $changeHistory = true)`

pretty much the same as in `Symfony\Bundle\FrameworkBundle\Client::request` except for `$method` and `$uri` parameters are swapped.

Some shortcut request methods:

`protected function get($uri, $parameters = [])`

`protected function post($uri, $content = '', $parameters = [])`
    
`protected function put($uri, $content = '')`
    
`protected function patch($uri, $content = '')`
    
`protected function delete($uri, $content = '')`

Before request it's possible to set some parameters using

`protected function setParameters(array $parameters)`

Request parameters set with this method are sent only with next single subsequent request.

##### Response and assertions

After request is made, it's possible to reach the response via `$this->response` (instance of `Symfony\Component\HttpFoundation\Response`).

Currently only single assertion implemented for response status code, accessible as `$this->assertStatusCode($expectedCode)`.
