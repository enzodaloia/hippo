<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217154029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dossier (id INT AUTO_INCREMENT NOT NULL, ext_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_3D48E0375D04BFAD (ext_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fichier (id INT AUTO_INCREMENT NOT NULL, folder_id INT NOT NULL, ext VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9B76551F162CB942 (folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, ext VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E0375D04BFAD FOREIGN KEY (ext_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE fichier ADD CONSTRAINT FK_9B76551F162CB942 FOREIGN KEY (folder_id) REFERENCES dossier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E0375D04BFAD');
        $this->addSql('ALTER TABLE fichier DROP FOREIGN KEY FK_9B76551F162CB942');
        $this->addSql('DROP TABLE dossier');
        $this->addSql('DROP TABLE fichier');
        $this->addSql('DROP TABLE images');
    }
}
