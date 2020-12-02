<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorTest extends WebTestCase
{
    public function testAuhtorCreate()
    {
        $name = "Ivan";

        $client = static::createClient();
        $client->request('POST', '/author/create', ['name' => $name]);
        $this->assertResponseIsSuccessful();
    }
}
