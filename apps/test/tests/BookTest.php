<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookTest extends WebTestCase
{
    public function testBookCreate()
    {
        $enName = "hello";
        $ruName = "privet";

        $client = static::createClient();
        $client->request('POST', '/book/create', ['name' => ['en' => $enName, 'ru' => $ruName]]);
        $this->assertResponseIsSuccessful();

        $book = json_decode($client->getResponse()->getContent());

        $client->request('GET', "/ru/book/{$book->id}");
        $this->assertResponseIsSuccessful();

        $book = json_decode($client->getResponse()->getContent());
        $this->assertEquals($book->Name, $ruName);

        $client->request('GET', "/book/search?query={$ruName}");
        $this->assertResponseIsSuccessful();

        $result = json_decode($client->getResponse()->getContent());
        $this->assertContains($ruName, $result->books[0]->name);
    }
}
