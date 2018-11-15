<?php
namespace Tests\TestApp\Bundles\SimpleAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MethodsController extends Controller
{
    public function getAction() {
        return new Response('get ok', 200);
    }
    
    public function postAction() {
        return new Response('post ok', 200);
    }
    
    public function putAction() {
        return new Response('put ok', 200);
    }
    
    public function patchAction() {
        return new Response('patch ok', 200);
    }
    
    public function deleteAction() {
        return new Response('delete ok', 200);
    }
}
