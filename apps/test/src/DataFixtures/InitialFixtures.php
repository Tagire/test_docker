<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Author;
use App\Entity\Book;

class InitialFixtures extends Fixture
{
    private const COUNT = 10000;
    private const MAX_AUTHORS_COUNT = 3;

    public function load(ObjectManager $manager)
    {
        $authors = [];
        $faker = \Faker\Factory::create('ru_RU');

        for ($i = 0; $i < self::COUNT; $i++) {
            $author = new Author();
            $author->setName($faker->name);
            $manager->persist($author);
            $authors[] = $author;
        }

        $manager->flush();

        for ($i = 0; $i < self::COUNT; $i++) {
            $book = new Book();
            $name = $faker->realText(40, 2);
            $book->translate('ru')->setName($name);
            $book->translate('en')->setName(
                transliterator_transliterate('Russian-Latin/BGN', $name)
            );

            for ($authorsCount = 0; $authorsCount < rand(1, self::MAX_AUTHORS_COUNT); $authorsCount++) {
                $book->addAuthor($authors[rand(0, self::COUNT-1)]);
            }

            $manager->persist($book);

            $book->mergeNewTranslations();
        }

        $manager->flush();
    }
}
