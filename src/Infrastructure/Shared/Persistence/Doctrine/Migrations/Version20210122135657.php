<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210122135657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pizza ALTER COLUMN created_at SET DEFAULT now()');

        $this->addSql(<<<SQL
CREATE OR REPLACE FUNCTION update_modified_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = now();
    RETURN NEW;
END;
$$ language 'plpgsql';
SQL
        );

        $this->addSql(<<<SQL
CREATE TRIGGER update_pizza_modtime BEFORE UPDATE ON pizza
FOR EACH ROW EXECUTE PROCEDURE update_modified_column();
SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pizza ALTER COLUMN created_at DROP DEFAULT');
        $this->addSql('DROP TRIGGER update_pizza_modtime ON pizza');
        $this->addSql('DROP FUNCTION IF EXISTS update_modified_column()');
    }
}
