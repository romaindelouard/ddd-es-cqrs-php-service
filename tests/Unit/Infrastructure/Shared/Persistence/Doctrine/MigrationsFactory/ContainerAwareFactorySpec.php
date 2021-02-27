<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\MigrationsFactory;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\MigrationFactory;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\MigrationsFactory\ContainerAwareFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerAwareFactorySpec extends ObjectBehavior
{
    public function let(
        Connection $connection,
        LoggerInterface $logger,
        ContainerInterface $container
    ) {
        $this->beConstructedWith($connection, $logger, $container);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ContainerAwareFactory::class);
        $this->shouldImplement(MigrationFactory::class);
    }

    public function it_should_create_a_version()
    {
        $this->createVersion(MigrationTest::class);
    }

    public function it_should_create_a_version_with_a_container()
    {
        $this->createVersion(MigrationWithContainerTest::class);
    }
}

class MigrationTest extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        return;
    }
}

class MigrationWithContainerTest extends AbstractMigration implements ContainerAwareInterface
{
    public function up(Schema $schema): void
    {
        return;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        return;
    }
}
