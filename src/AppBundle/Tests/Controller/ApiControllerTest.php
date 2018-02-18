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
    }

    public function testWct()
    {
        $client = static::createClient();

        // Act as a mobile with bad parameters
        $client->request(
            'GET',
            '/collect?v=1&ds=web&tid=UA-1234-5&t=pageview&wui=94760d4b3e58797ae1',
            array(),
            array(),
            array(
                'HTTP_USER_AGENT'          => 'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19',
            )
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertContains("This value is not valid.", $client->getResponse()->getContent());

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

        // Web request without wct shouldn't raise an error
        $client->request(
            'GET',
            '/collect?v=1&ds=web&tid=UA-1234-5&t=pageview&wuui=94760d4b3e58797ae1',
            array(),
            array(),
            array(
                'HTTP_USER_AGENT'          => 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)',
            )
        );
        
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
  
    }
}
