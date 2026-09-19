<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260919000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add access validation timestamps to tickets.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ticket ADD validated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD validated_by UUID DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ticket DROP validated_by');
        $this->addSql('ALTER TABLE ticket DROP validated_at');
    }
}