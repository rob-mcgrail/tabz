<?php

namespace TabApp\HomeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $outcome = $crawler->filter('html:contains("Hi want some tabs??")');

        $outcome = $outcome->count() > 0;

        $this->assertTrue($outcome);
    }
}
