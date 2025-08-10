<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250810151536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE website_status_log (id INT AUTO_INCREMENT NOT NULL, website_id_id INT NOT NULL, status_code INT NOT NULL, checked_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', response_time DOUBLE PRECISION NOT NULL, INDEX IDX_D07DF642282E2E9B (website_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE website_status_log ADD CONSTRAINT FK_D07DF642282E2E9B FOREIGN KEY (website_id_id) REFERENCES website (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE website_status_log DROP FOREIGN KEY FK_D07DF642282E2E9B');
        $this->addSql('DROP TABLE website_status_log');
    }
}
