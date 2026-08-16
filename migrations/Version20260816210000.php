<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260816210000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Move currency from zone to event.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE event ADD currency VARCHAR(3) DEFAULT 'PEN' NOT NULL");
        $this->addSql("UPDATE event e SET currency = COALESCE((SELECT z.currency FROM zone z INNER JOIN day d ON d.id = z.day_id WHERE d.event_id = e.id LIMIT 1), e.currency)");
        $this->addSql('ALTER TABLE event ALTER currency DROP DEFAULT');
        $this->addSql('ALTER TABLE zone DROP currency');
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE zone ADD currency VARCHAR(3) DEFAULT 'PEN' NOT NULL");
        $this->addSql("UPDATE zone z SET currency = COALESCE((SELECT e.currency FROM day d INNER JOIN event e ON e.id = d.event_id WHERE d.id = z.day_id), z.currency)");
        $this->addSql('ALTER TABLE zone ALTER currency DROP DEFAULT');
        $this->addSql('ALTER TABLE event DROP currency');
    }
}
