<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Command\Pizza\Create;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Command\Pizza\Create\CreateCommand;

class CreateCommandSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            '3d211801-4d00-4f64-9123-bf61c0065349',
            '4 cheeses',
            'it is a wonderfully pizza'
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CreateCommand::class);
    }
}
