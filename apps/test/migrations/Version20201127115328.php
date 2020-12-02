<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127115328 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add fulltext indexes';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE FULLTEXT INDEX name ON book_translation (name)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX name ON book_translation');
    }
}
