<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Event\ReadModel;

use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Elasticsearch\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Infrastructure\Shared\Event\ReadModel\ElasticSearchEventRepository;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Repository\AbstractElasticSearchRepository;

class ElasticSearchEventRepositorySpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ElasticSearchEventRepository::class);
        $this->shouldBeAnInstanceOf(AbstractElasticSearchRepository::class);
    }

    public function it_should_store_a_event(Client $client)
    {
        $message = DomainMessageTest::create();

        $client
            ->index(Argument::any())
            ->willReturn([])
            ->shouldBeCalledTimes(1);

        $this->store($message);
    }
}

class DomainMessageTest
{
    public static function create()
    {
        $id = 'VIP Id';
        $payload = new SomeEvent();
        $playhead = 15;
        $metadata = new Metadata(['meta']);

        return DomainMessage::recordNow($id, $playhead, $metadata, $payload);
    }
}

class SomeEvent
{
    public function serialize()
    {
        return '';
    }
}
