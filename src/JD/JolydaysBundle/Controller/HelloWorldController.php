<?php

namespace JD\JolydaysBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

class HelloWorldController extends Controller
{
    public function indexAction()
    {
        return new Response('Hello World');
    }
}
