<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Repository;

use Elasticsearch\Client;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Repository\AbstractElasticSearchRepository;

class AbstractElasticSearchRepositorySpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beAnInstanceOf(AbstractElasticSearchRepositoryMock::class);
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AbstractElasticSearchRepository::class);
    }

    public function it_should_search(Client $client)
    {
        $query = ['word'];
        $result = ['result'];
        $client
            ->search([
                'index' => AbstractElasticSearchRepositoryMock::INDEX_NAME,
                'body' => $query,
            ])
            ->willReturn($result)
            ->shouldBeCalledTimes(1);
        $this->search($query)->shouldBe($result);
    }
}

class AbstractElasticSearchRepositoryMock extends AbstractElasticSearchRepository
{
    public const INDEX_NAME = 'myindex';

    protected function index(): string
    {
        return self::INDEX_NAME;
    }
}
