<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260106064751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_like (id INT AUTO_INCREMENT NOT NULL, ip_hash VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, event_id INT NOT NULL, INDEX IDX_B3A80C1871F7E88B (event_id), UNIQUE INDEX unique_event_ip (event_id, ip_hash), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE event_like ADD CONSTRAINT FK_B3A80C1871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_like DROP FOREIGN KEY FK_B3A80C1871F7E88B');
        $this->addSql('DROP TABLE event_like');
    }
}
