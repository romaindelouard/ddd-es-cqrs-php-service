<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210122091645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
CREATE TABLE pizza (
    uuid UUID PRIMARY KEY DEFAULT gen_random_uuid() NOT NULL, 
    name VARCHAR(255) NOT NULL, 
    description VARCHAR(255) DEFAULT NULL, 
    created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pizza');
    }
}
