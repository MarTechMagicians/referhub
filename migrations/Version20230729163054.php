<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230729163054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE referral_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE referral_code_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE webhook_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, referral_code_id INT NOT NULL, referred_user_id INT NOT NULL, creator_user_id INT NOT NULL, event_type VARCHAR(255) NOT NULL, event_data JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA77EFAA231 ON event (referral_code_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA74C7D8DD1 ON event (referred_user_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA729FC6AE1 ON event (creator_user_id)');
        $this->addSql('CREATE TABLE referral (id INT NOT NULL, referred_user_id INT NOT NULL, creator_user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_73079D004C7D8DD1 ON referral (referred_user_id)');
        $this->addSql('CREATE INDEX IDX_73079D0029FC6AE1 ON referral (creator_user_id)');
        $this->addSql('CREATE TABLE referral_referral_code (referral_id INT NOT NULL, referral_code_id INT NOT NULL, PRIMARY KEY(referral_id, referral_code_id))');
        $this->addSql('CREATE INDEX IDX_8B0E3BAA3CCAA4B7 ON referral_referral_code (referral_id)');
        $this->addSql('CREATE INDEX IDX_8B0E3BAA7EFAA231 ON referral_referral_code (referral_code_id)');
        $this->addSql('CREATE TABLE referral_code (id INT NOT NULL, creator_user_id INT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6447454A29FC6AE1 ON referral_code (creator_user_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, identification_method VARCHAR(255) NOT NULL, identification_value VARCHAR(512) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE webhook (id INT NOT NULL, creator_user_id INT NOT NULL, url VARCHAR(2048) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A74175629FC6AE1 ON webhook (creator_user_id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77EFAA231 FOREIGN KEY (referral_code_id) REFERENCES referral_code (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA74C7D8DD1 FOREIGN KEY (referred_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA729FC6AE1 FOREIGN KEY (creator_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE referral ADD CONSTRAINT FK_73079D004C7D8DD1 FOREIGN KEY (referred_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE referral ADD CONSTRAINT FK_73079D0029FC6AE1 FOREIGN KEY (creator_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE referral_referral_code ADD CONSTRAINT FK_8B0E3BAA3CCAA4B7 FOREIGN KEY (referral_id) REFERENCES referral (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE referral_referral_code ADD CONSTRAINT FK_8B0E3BAA7EFAA231 FOREIGN KEY (referral_code_id) REFERENCES referral_code (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE referral_code ADD CONSTRAINT FK_6447454A29FC6AE1 FOREIGN KEY (creator_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE webhook ADD CONSTRAINT FK_8A74175629FC6AE1 FOREIGN KEY (creator_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE referral_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE referral_code_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE webhook_id_seq CASCADE');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA77EFAA231');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA74C7D8DD1');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA729FC6AE1');
        $this->addSql('ALTER TABLE referral DROP CONSTRAINT FK_73079D004C7D8DD1');
        $this->addSql('ALTER TABLE referral DROP CONSTRAINT FK_73079D0029FC6AE1');
        $this->addSql('ALTER TABLE referral_referral_code DROP CONSTRAINT FK_8B0E3BAA3CCAA4B7');
        $this->addSql('ALTER TABLE referral_referral_code DROP CONSTRAINT FK_8B0E3BAA7EFAA231');
        $this->addSql('ALTER TABLE referral_code DROP CONSTRAINT FK_6447454A29FC6AE1');
        $this->addSql('ALTER TABLE webhook DROP CONSTRAINT FK_8A74175629FC6AE1');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE referral');
        $this->addSql('DROP TABLE referral_referral_code');
        $this->addSql('DROP TABLE referral_code');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE webhook');
    }
}
