<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260829200000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add timezone and event defaults to companies.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE company ADD timezone VARCHAR(64) DEFAULT 'America/Lima' NOT NULL");
        $this->addSql("ALTER TABLE company ADD company_default JSON DEFAULT '{\"currency\":\"PEN\",\"taxRate\":0}' NOT NULL");
        $this->addSql('ALTER TABLE company ALTER timezone DROP DEFAULT');
        $this->addSql('ALTER TABLE company ALTER company_default DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE company DROP timezone');
        $this->addSql('ALTER TABLE company DROP company_default');
    }
}
