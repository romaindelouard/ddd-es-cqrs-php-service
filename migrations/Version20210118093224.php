<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210118093224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
CREATE EXTENSION IF NOT EXISTS pgcrypto;
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<SQL
DROP EXTENSION pgcrypto
SQL
        );
    }
}
