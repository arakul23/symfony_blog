<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260527070625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_tags (blog_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_8F6C18B6DAE07E97 (blog_id), INDEX IDX_8F6C18B6BAD26311 (tag_id), PRIMARY KEY (blog_id, tag_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE blog_tags ADD CONSTRAINT FK_8F6C18B6DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE blog_tags ADD CONSTRAINT FK_8F6C18B6BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_tags DROP FOREIGN KEY FK_8F6C18B6DAE07E97');
        $this->addSql('ALTER TABLE blog_tags DROP FOREIGN KEY FK_8F6C18B6BAD26311');
        $this->addSql('DROP TABLE blog_tags');
        $this->addSql('DROP TABLE tag');
    }
}
