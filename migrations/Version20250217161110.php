<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217161110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E0375D04BFAD');
        $this->addSql('ALTER TABLE fichier DROP FOREIGN KEY FK_9B76551F5D04BFAD');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP INDEX IDX_3D48E0375D04BFAD ON dossier');
        $this->addSql('ALTER TABLE dossier ADD ext VARCHAR(255) NOT NULL, DROP ext_id');
        $this->addSql('DROP INDEX IDX_9B76551F5D04BFAD ON fichier');
        $this->addSql('ALTER TABLE fichier ADD ext VARCHAR(255) NOT NULL, DROP ext_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, ext VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE dossier ADD ext_id INT DEFAULT NULL, DROP ext');
        $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E0375D04BFAD FOREIGN KEY (ext_id) REFERENCES images (id)');
        $this->addSql('CREATE INDEX IDX_3D48E0375D04BFAD ON dossier (ext_id)');
        $this->addSql('ALTER TABLE fichier ADD ext_id INT NOT NULL, DROP ext');
        $this->addSql('ALTER TABLE fichier ADD CONSTRAINT FK_9B76551F5D04BFAD FOREIGN KEY (ext_id) REFERENCES images (id)');
        $this->addSql('CREATE INDEX IDX_9B76551F5D04BFAD ON fichier (ext_id)');
    }
}
