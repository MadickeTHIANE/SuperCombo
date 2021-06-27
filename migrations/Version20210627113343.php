<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210627113343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_billet (id INT AUTO_INCREMENT NOT NULL, auteur VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_discussion (id INT AUTO_INCREMENT NOT NULL, id_billet_id INT DEFAULT NULL, auteur VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_267FDBE6C4A2805F (id_billet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_discussion ADD CONSTRAINT FK_267FDBE6C4A2805F FOREIGN KEY (id_billet_id) REFERENCES blog_billet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_discussion DROP FOREIGN KEY FK_267FDBE6C4A2805F');
        $this->addSql('DROP TABLE blog_billet');
        $this->addSql('DROP TABLE blog_discussion');
    }
}
