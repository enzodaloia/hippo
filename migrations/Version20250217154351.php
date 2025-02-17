<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217154351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier ADD ext_id INT NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE fichier ADD CONSTRAINT FK_9B76551F5D04BFAD FOREIGN KEY (ext_id) REFERENCES images (id)');
        $this->addSql('CREATE INDEX IDX_9B76551F5D04BFAD ON fichier (ext_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier DROP FOREIGN KEY FK_9B76551F5D04BFAD');
        $this->addSql('DROP INDEX IDX_9B76551F5D04BFAD ON fichier');
        $this->addSql('ALTER TABLE fichier DROP ext_id, DROP created_at');
    }
}
