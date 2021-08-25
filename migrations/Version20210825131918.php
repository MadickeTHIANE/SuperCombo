<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210825131918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_billet ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_billet ADD CONSTRAINT FK_8A4CEF37A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8A4CEF37A76ED395 ON blog_billet (user_id)');
        $this->addSql('ALTER TABLE blog_discussion ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_discussion ADD CONSTRAINT FK_267FDBE6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_267FDBE6A76ED395 ON blog_discussion (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_billet DROP FOREIGN KEY FK_8A4CEF37A76ED395');
        $this->addSql('DROP INDEX IDX_8A4CEF37A76ED395 ON blog_billet');
        $this->addSql('ALTER TABLE blog_billet DROP user_id');
        $this->addSql('ALTER TABLE blog_discussion DROP FOREIGN KEY FK_267FDBE6A76ED395');
        $this->addSql('DROP INDEX IDX_267FDBE6A76ED395 ON blog_discussion');
        $this->addSql('ALTER TABLE blog_discussion DROP user_id');
    }
}
