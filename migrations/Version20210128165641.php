<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210128165641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
DROP TABLE events;
SQL
        );
        $this->addSql(<<<SQL
CREATE TABLE events (
    id bigserial NOT NULL,
    uuid uuid NOT NULL,
    playhead integer NOT NULL,
    payload text NOT NULL,
    metadata text NOT NULL,
    recorded_on character varying(32) NOT NULL,
    type character varying(255) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE (uuid, playhead)
)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
DROP TABLE events;
SQL
        );
        $this->addSql(<<<SQL
CREATE TABLE events (
    id bigint NOT NULL,
    uuid bytea NOT NULL,
    playhead integer NOT NULL,
    payload text NOT NULL,
    metadata text NOT NULL,
    recorded_on character varying(32) NOT NULL,
    type character varying(255) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE (uuid, playhead)
)
SQL
        );
    }
}
