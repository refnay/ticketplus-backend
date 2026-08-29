<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260828000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add event, paid date and PEN per USD exchange rate to purchases.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "purchase" ADD event_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE "purchase" ADD paid_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE "purchase" ADD exchange_rate DOUBLE PRECISION DEFAULT NULL');
        $this->addSql("UPDATE \"purchase\" SET event_id = (details->>'event')::UUID WHERE details->>'event' IS NOT NULL");
        $this->addSql('UPDATE "purchase" SET paid_at = updated_at WHERE status = 1 AND paid_at IS NULL');
        $this->addSql("UPDATE \"purchase\" SET exchange_rate = 1 WHERE status = 1 AND currency = 'PEN' AND exchange_rate IS NULL");
        $this->addSql('ALTER TABLE "purchase" ALTER event_id SET NOT NULL');
        $this->addSql('CREATE INDEX IDX_6117D13B71F7E88B ON "purchase" (event_id)');
        $this->addSql('CREATE INDEX IDX_PURCHASE_APPROVED_SALES ON "purchase" (event_id, status, paid_at)');
        $this->addSql('ALTER TABLE "purchase" ADD CONSTRAINT FK_6117D13B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "purchase" DROP CONSTRAINT FK_6117D13B71F7E88B');
        $this->addSql('DROP INDEX IDX_6117D13B71F7E88B');
        $this->addSql('DROP INDEX IDX_PURCHASE_APPROVED_SALES');
        $this->addSql('ALTER TABLE "purchase" DROP event_id');
        $this->addSql('ALTER TABLE "purchase" DROP paid_at');
        $this->addSql('ALTER TABLE "purchase" DROP exchange_rate');
    }
}
