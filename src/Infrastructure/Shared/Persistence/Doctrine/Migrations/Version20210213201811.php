<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210213201811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
CREATE TABLE "user" (
    uuid UUID NOT NULL, 
    created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE, 
    credentials_email VARCHAR(255) NOT NULL, 
    credentials_password VARCHAR(255) NOT NULL, 
    PRIMARY KEY(uuid)
);
SQL
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649299C9369 ON "user" (credentials_email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE "user"');
    }
}
