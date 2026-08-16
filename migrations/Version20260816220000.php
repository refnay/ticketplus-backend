<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260816220000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Move tax rate from zone to event.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE event ADD tax_rate DOUBLE PRECISION DEFAULT 0 NOT NULL");
        $this->addSql("UPDATE event e SET tax_rate = COALESCE((SELECT z.tax_rate FROM zone z INNER JOIN day d ON d.id = z.day_id WHERE d.event_id = e.id LIMIT 1), e.tax_rate)");
        $this->addSql('ALTER TABLE event ALTER tax_rate DROP DEFAULT');
        $this->addSql('ALTER TABLE zone DROP tax_rate');
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE zone ADD tax_rate DOUBLE PRECISION DEFAULT 0 NOT NULL");
        $this->addSql("UPDATE zone z SET tax_rate = COALESCE((SELECT e.tax_rate FROM day d INNER JOIN event e ON e.id = d.event_id WHERE d.id = z.day_id), z.tax_rate)");
        $this->addSql('ALTER TABLE zone ALTER tax_rate DROP DEFAULT');
        $this->addSql('ALTER TABLE event DROP tax_rate');
    }
}
