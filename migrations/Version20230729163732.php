<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230729163732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE referral_referral_code DROP CONSTRAINT fk_8b0e3baa3ccaa4b7');
        $this->addSql('ALTER TABLE referral_referral_code DROP CONSTRAINT fk_8b0e3baa7efaa231');
        $this->addSql('DROP TABLE referral_referral_code');
        $this->addSql('ALTER TABLE referral ADD referral_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE referral ADD CONSTRAINT FK_73079D007EFAA231 FOREIGN KEY (referral_code_id) REFERENCES referral_code (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_73079D007EFAA231 ON referral (referral_code_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE referral_referral_code (referral_id INT NOT NULL, referral_code_id INT NOT NULL, PRIMARY KEY(referral_id, referral_code_id))');
        $this->addSql('CREATE INDEX idx_8b0e3baa7efaa231 ON referral_referral_code (referral_code_id)');
        $this->addSql('CREATE INDEX idx_8b0e3baa3ccaa4b7 ON referral_referral_code (referral_id)');
        $this->addSql('ALTER TABLE referral_referral_code ADD CONSTRAINT fk_8b0e3baa3ccaa4b7 FOREIGN KEY (referral_id) REFERENCES referral (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE referral_referral_code ADD CONSTRAINT fk_8b0e3baa7efaa231 FOREIGN KEY (referral_code_id) REFERENCES referral_code (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE referral DROP CONSTRAINT FK_73079D007EFAA231');
        $this->addSql('DROP INDEX IDX_73079D007EFAA231');
        $this->addSql('ALTER TABLE referral DROP referral_code_id');
    }
}
