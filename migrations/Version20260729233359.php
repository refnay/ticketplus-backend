<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260729233359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_member (id UUID NOT NULL, role INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, member_id UUID NOT NULL, company_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_4D7B9E0D7597D3FE ON company_member (member_id)');
        $this->addSql('CREATE INDEX IDX_4D7B9E0D979B1AD6 ON company_member (company_id)');
        $this->addSql('ALTER TABLE company_member ADD CONSTRAINT FK_4D7B9E0D7597D3FE FOREIGN KEY (member_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE company_member ADD CONSTRAINT FK_4D7B9E0D979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d649979b1ad6');
        $this->addSql('DROP INDEX idx_8d93d649979b1ad6');
        $this->addSql('ALTER TABLE "user" DROP company_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_member DROP CONSTRAINT FK_4D7B9E0D7597D3FE');
        $this->addSql('ALTER TABLE company_member DROP CONSTRAINT FK_4D7B9E0D979B1AD6');
        $this->addSql('DROP TABLE company_member');
        $this->addSql('ALTER TABLE "user" ADD company_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d649979b1ad6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8d93d649979b1ad6 ON "user" (company_id)');
    }
}
