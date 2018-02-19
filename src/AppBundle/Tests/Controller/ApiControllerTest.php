<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    public function testCollect()
    {
        $client = static::createClient();

        $crawler = $client->request('PUT', '/collect');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to the Api:collect page', $crawler->filter('h1')->text());
    }


    public function testV()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/collect');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertContains('{"property_path":"v","message":"This value should not be blank."}', $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/collect?v=2');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertContains('{"property_path":"v","message":"This value should be equal to 1."}', $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/collect?v=1');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertNotContains('{"property_path":"v"', $client->getResponse()->getContent());
    }

    public function testT()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/collect?t=notavalidchoice');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertContains('{"property_path":"t","message":"The value you selected is not a valid choice."}', $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/collect?t=pageview');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertNotContains('{"property_path":"t"', $client->getResponse()->getContent());
    }

    public function testDl()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/collect?dl=noturl');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertContains('{"property_path":"dl","message":"The url \'\"noturl\"\' is not a valid url"}', $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/collect?dl=http%3A%2F%2Fwww.wizbii.com%2Fcompany%2Fwizbii');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertNotContains('{"property_path":"dl"', $client->getResponse()->getContent());
    }

    public function testDr()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/collect?dr=noturl');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertContains('{"property_path":"dr","message":"The url \'\"noturl\"\' is not a valid url"}', $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/collect?dr=http%3A%2F%2Fwww.jobijoba.com%2F/whatever');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertNotContains('{"property_path":"dr"', $client->getResponse()->getContent());
    }

    public function testWct()
    {
        $client = static::createClient();

        // Act as a mobile with bad parameters
        $client->request(
            'GET',
            '/collect?wct=notvalidwct',
            array(),
            array(),
            array(
                'HTTP_USER_AGENT'          => 'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19',
            )
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertContains('{"property_path":"wct","message":"This value is not valid."}', $client->getResponse()->getContent());

        // Act as a mobile with good parameters
        $client->request(
            'GET',
            '/collect?v=1&wct=visitor&ds=web&tid=UA-1234-5&t=pageview&wui=94760d4b3e58797ae1',
            array(),
            array(),
            array(
                'HTTP_USER_AGENT'          => 'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19',
            )
        );
        
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Web request with bad wct shouldn't raise an error
        $client->request(
            'GET',
            '/collect?v=1&ds=web&tid=UA-1234-5&t=pageview&wuui=94760d4b3e58797ae1&wct=notvalidwct',
            array(),
            array(),
            array(
                'HTTP_USER_AGENT'          => 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)',
            )
        );
        
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
  
    }
}
