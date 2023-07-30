<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230730210012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webhook ADD referral_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE webhook ADD CONSTRAINT FK_8A7417567EFAA231 FOREIGN KEY (referral_code_id) REFERENCES referral_code (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8A7417567EFAA231 ON webhook (referral_code_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE webhook DROP CONSTRAINT FK_8A7417567EFAA231');
        $this->addSql('DROP INDEX IDX_8A7417567EFAA231');
        $this->addSql('ALTER TABLE webhook DROP referral_code_id');
    }
}
