<?php
namespace Tests\TestApp\Bundles\SimpleAppBundle\Controller;

class HtmlController
{
    public function htmlAction() {
        $content = '<!doctype html>'
                . '<html>'
                . '     <head>'
                . '     </head>'
                . '     <body>'
                . '         <h1>Success!</h1>'
                . '     </body>'
                . '</html>';
        return new \Symfony\Component\HttpFoundation\Response($content, 200);
    }
}
