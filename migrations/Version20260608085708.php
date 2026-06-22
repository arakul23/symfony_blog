<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260608085708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // 1. First, fill in the NULL gaps with default values
        $this->addSql('UPDATE blog SET percent = 0 WHERE percent IS NULL');
        $this->addSql('UPDATE blog SET status = "draft" WHERE status IS NULL'); // Adjust "draft" to your default
        $this->addSql('UPDATE blog SET blocked_at = NOW() WHERE blocked_at IS NULL'); // Or a specific past date

// 2. Now it's safe to alter the columns to NOT NULL
        $this->addSql('ALTER TABLE blog ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, CHANGE percent percent SMALLINT DEFAULT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE blocked_at blocked_at DATETIME DEFAULT NULL');

// 3. The rest of your migration can proceed as normal
        $this->addSql('ALTER TABLE category ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP created_at, DROP updated_at, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE blocked_at blocked_at DATETIME DEFAULT NULL, CHANGE percent percent SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE category DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE tag DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE user DROP created_at, DROP updated_at');
    }
}
