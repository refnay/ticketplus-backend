<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260829120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add venue, coordinates, logo and thumbnail to events.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event ADD venue VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD coordinates JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD thumbnail VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event DROP venue');
        $this->addSql('ALTER TABLE event DROP coordinates');
        $this->addSql('ALTER TABLE event DROP logo');
        $this->addSql('ALTER TABLE event DROP thumbnail');
    }
}
