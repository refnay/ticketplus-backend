<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260829184217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add sale_start_at to day and order_limit to event';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE day ADD sale_start_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP'
        );
        $this->addSql(
            'ALTER TABLE day ALTER COLUMN sale_start_at SET NOT NULL'
        );
        $this->addSql(
            'ALTER TABLE day ALTER COLUMN sale_start_at DROP DEFAULT'
        );

        $this->addSql(
            'ALTER TABLE event ADD order_limit INT DEFAULT 10'
        );
        $this->addSql(
            'ALTER TABLE event ALTER COLUMN order_limit SET NOT NULL'
        );
        $this->addSql(
            'ALTER TABLE event ALTER COLUMN order_limit DROP DEFAULT'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE day DROP sale_start_at');
        $this->addSql('ALTER TABLE event DROP order_limit');
    }
}