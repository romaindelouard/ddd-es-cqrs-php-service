<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\ReadModel;

use Broadway\ReadModel\SerializableReadModel;
use Broadway\Serializer\Serializable;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\UserView;

class UserViewSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserView::class);
        $this->shouldImplement(SerializableReadModel::class);
    }

    public function it_should_create_an_user_view_from_data_seralized(
        Serializable $event
    ) {
        $uuid = 'd699cc28-eeda-48c3-a279-7b0feb5f4d43';
        $email = 'toto@domain.com';
        $event->serialize()->willReturn([
            'uuid' => $uuid,
            'credentials' => [
                'email' => $email,
                'password' => 'mypassword',
            ],
            'created_at' => '2021-02-18T15:14:56.000000+00',
        ]);

        $this->beConstructedThrough('fromSerializable', [$event]);

        $this->uuid()->toString()->shouldBe($uuid);
        $this->getId()->shouldBe($uuid);
        $this->email()->shouldBe($email);

        $this->serialize()->shouldBe([
            'uuid' => $uuid,
            'credentials' => ['email' => $email],
        ]);
    }
}
