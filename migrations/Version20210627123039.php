<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210627123039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_discussion DROP FOREIGN KEY FK_267FDBE6C4A2805F');
        $this->addSql('DROP INDEX IDX_267FDBE6C4A2805F ON blog_discussion');
        $this->addSql('ALTER TABLE blog_discussion CHANGE id_billet_id billet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_discussion ADD CONSTRAINT FK_267FDBE644973C78 FOREIGN KEY (billet_id) REFERENCES blog_billet (id)');
        $this->addSql('CREATE INDEX IDX_267FDBE644973C78 ON blog_discussion (billet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_discussion DROP FOREIGN KEY FK_267FDBE644973C78');
        $this->addSql('DROP INDEX IDX_267FDBE644973C78 ON blog_discussion');
        $this->addSql('ALTER TABLE blog_discussion CHANGE billet_id id_billet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_discussion ADD CONSTRAINT FK_267FDBE6C4A2805F FOREIGN KEY (id_billet_id) REFERENCES blog_billet (id)');
        $this->addSql('CREATE INDEX IDX_267FDBE6C4A2805F ON blog_discussion (id_billet_id)');
    }
}
