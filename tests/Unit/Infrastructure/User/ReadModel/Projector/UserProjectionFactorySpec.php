<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\ReadModel\Projector;

use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Broadway\ReadModel\Projector;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Domain\Event\User\UserEmailChanged;
use Romaind\PizzaStore\Domain\Event\User\UserWasCreated;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres\PostgresReadModelUserRepository;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Projector\UserProjectionFactory;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\UserView;

class UserProjectionFactorySpec extends ObjectBehavior
{
    public function let(PostgresReadModelUserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserProjectionFactory::class);
        $this->shouldBeAnInstanceOf(Projector::class);
    }

    public function it_should_handle_an_user_was_created_event(
        PostgresReadModelUserRepository $repository
    ) {
        $repository
            ->add(Argument::allOf(
                Argument::type(UserView::class),
                Argument::that(fn ($userView) => UserWasCreatedTest::$uuidString === $userView->getId()),
                Argument::that(fn ($userView) => UserWasCreatedTest::$emailString === $userView->email())
            ))
            ->shouldBeCalledTimes(1);

        $this->handle(UserWasCreatedTest::create());
    }

    public function it_should_handle_an_user_email_changed_event(
        PostgresReadModelUserRepository $repository,
        UserView $userView
    ) {
        $repository
            ->oneByUuid(UserEmailChangedTest::$uuidString)
            ->willReturn($userView)
            ->shouldBeCalledTimes(1);
        $repository->apply()->shouldBeCalledTimes(1);

        $this->handle(UserEmailChangedTest::create());
    }
}
class UserWasCreatedTest
{
    public static string $uuidString = '648fa6ea-74de-4ae5-9752-910e1494d54c';
    public static string $emailString = 'toto@domain.com';
    public static string $password = 'mypassword';
    public static string $dateString = '2021-02-18T15:14:56.000000';

    public static function create()
    {
        $id = 'VIP Id';
        $payload = UserWasCreated::deserialize([
            'uuid' => self::$uuidString,
            'credentials' => [
                'email' => self::$emailString,
                'password' => self::$password,
            ],
            'created_at' => self::$dateString,
        ]);
        $playhead = 15;
        $metadata = new Metadata(['meta']);

        return DomainMessage::recordNow($id, $playhead, $metadata, $payload);
    }
}

class UserEmailChangedTest
{
    public static string $uuidString = 'c036a189-32b8-4a20-8682-a6ea35a099ee';
    public static string $emailString = 'toto@domain.com';
    public static string $dateString = '2021-02-18T15:14:56.000000';

    public static function create()
    {
        $id = 'VIP Id';
        $payload = UserEmailChanged::deserialize([
            'uuid' => self::$uuidString,
            'email' => self::$emailString,
            'updated_at' => self::$dateString,
        ]);
        $playhead = 15;
        $metadata = new Metadata(['meta']);

        return DomainMessage::recordNow($id, $playhead, $metadata, $payload);
    }
}
