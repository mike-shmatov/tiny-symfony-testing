<?php
namespace Tests\TestApp\Bundles\SimpleAppBundle\Controller;

class EchoController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    public function requestAction(\Symfony\Component\HttpFoundation\Request $request) {
        $response = new \stdClass();
        $response->headers = $request->headers->all();
        $response->cookies = $request->cookies->all();
        $response->post = $request->request->all();
        $response->get = $request->query->all();
        $response->server = $request->server->all();
        $response->content = $request->getContent();
        return $this->json($response);
    }
    
    public function throwAction() {
        throw new \RuntimeException('expected');
    }
}
