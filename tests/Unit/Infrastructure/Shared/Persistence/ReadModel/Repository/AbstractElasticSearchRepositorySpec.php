<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Repository;

use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Repository\AbstractElasticSearchRepository;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Repository\ElasticSearchRepositoryInterface;

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
        $this->shouldImplement(ElasticSearchRepositoryInterface::class);
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

    public function it_should_refresh(Client $client, IndicesNamespace $indices)
    {
        $index = ['index' => AbstractElasticSearchRepositoryMock::INDEX_NAME];
        $client->indices()
            ->willReturn($indices)
            ->shouldBeCalledTimes(2);
        $indices->exists($index)
            ->willReturn(true)
            ->shouldBeCalledTimes(1);
        $indices->refresh($index)
            ->shouldBeCalledTimes(1);

        $this->refresh();
    }

    public function it_should_delete(Client $client, IndicesNamespace $indices)
    {
        $index = ['index' => AbstractElasticSearchRepositoryMock::INDEX_NAME];
        $client->indices()
            ->willReturn($indices)
            ->shouldBeCalledTimes(2);
        $indices->exists($index)
            ->willReturn(true)
            ->shouldBeCalledTimes(1);
        $indices->delete($index)
            ->shouldBeCalledTimes(1);

        $this->delete();
    }

    public function it_should_reboot_with_index_exists(Client $client, IndicesNamespace $indices)
    {
        $index = ['index' => AbstractElasticSearchRepositoryMock::INDEX_NAME];
        $client->indices()
            ->willReturn($indices)
            ->shouldBeCalledTimes(3);
        $indices->exists($index)
            ->willReturn(true)
            ->shouldBeCalledTimes(2);
        $indices->delete($index)
            ->shouldBeCalledTimes(1);
        $indices->create($index)
            ->shouldNotBeCalled();

        $this->reboot();
    }

    public function it_should_reboot_without_index(Client $client, IndicesNamespace $indices)
    {
        $index = ['index' => AbstractElasticSearchRepositoryMock::INDEX_NAME];
        $client->indices()
            ->willReturn($indices)
            ->shouldBeCalledTimes(3);
        $indices->exists($index)
            ->willReturn(false)
            ->shouldBeCalledTimes(2);
        $indices->delete($index)
            ->shouldNotBeCalled();
        $indices->create($index)
            ->shouldBeCalledTimes(1);

        $this->reboot();
    }

    public function it_should_get_page_results(Client $client)
    {
        $client
            ->search([
                'index' => AbstractElasticSearchRepositoryMock::INDEX_NAME,
                'from' => 99,
                'size' => 33,
            ])
            ->willReturn([
                'hits' => ['hits' => [['_source' => 'oui']], 'total' => 154],
            ])
            ->shouldBeCalledTimes(1);

        $this->page(4, 33)->shouldBe([
            'data' => ['oui'],
            'total' => 154,
        ]);
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
