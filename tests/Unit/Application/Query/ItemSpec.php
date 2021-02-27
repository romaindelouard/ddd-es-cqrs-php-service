<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Query;

use Broadway\ReadModel\SerializableReadModel;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\Item;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\UserView;

class ItemSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough(
            'fromPayload',
            [
                'fdfb7256-89ea-4722-b978-413ec67d70e3',
                UserView::TYPE,
                [],
                [],
            ]
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Item::class);
    }

    public function it_should_create_a_item_from_serializable(
        SerializableReadModel $serializableReadModel
    ) {
        $serializableReadModel->getId()
            ->willReturn('5602a92e-6dfe-4678-ad75-9c2cff41270d')
            ->shouldBeCalledTimes(1);
        $serializableReadModel->serialize()
            ->willReturn([])
            ->shouldBeCalledTimes(1);

        $this::fromSerializable($serializableReadModel, []);
    }
}
