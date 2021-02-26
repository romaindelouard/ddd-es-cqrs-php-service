<?php

namespace Tests\Functional\Context;

use Assert\Assertion;
use Behat\Gherkin\Node\TableNode;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

class CommandContext extends AbstractContext
{
    protected Application $application;
    protected CommandTester $commandTester;
    protected \Exception $exception;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);

        putenv('SYMFONY_ENV=test');
        putenv('APP_ENV='.$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'test');
        $this->application = new Application($kernel);

        $command = $this->getApplication()->get('debug:event-dispatcher');
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @When I run :commandName command with:
     */
    public function iRunCommandWith($commandName, TableNode $table): void
    {
        $values = $table->getRowsHash();
        $input = $this->parseValues($values);
        $this->executeCommand($commandName, $input);
    }

    /**
     * @When I run :commandName command
     */
    public function iRunCommand($commandName): void
    {
        $this->executeCommand($commandName);
    }

    private function executeCommand($commandName, array $input = []): void
    {
        try {
            $command = $this->getApplication()->find($commandName);
            $this->commandTester = new CommandTester($command);
            $this->commandTester->execute($input);
        } catch (\Exception $exception) {
            $this->exception = $exception;
            dump($exception->getMessage(), $exception->getTraceAsString());
        }
    }

    /**
     * @throws Exception
     */
    private function getApplication(): Application
    {
        if (null == $this->application) {
            throw new \Exception('Application not initialized. Kernel not provided');
        }

        return $this->application;
    }

    private function parseValues(array $values): array
    {
        return array_map(
            function ($value) {
                return '(file) ' === substr($value, 0, 7)
                  ? sprintf('%s/../%s', __DIR__, substr($value, 7))
                  : $value;
            },
            $values
        );
    }

    /**
     * @Then command output should contain :message
     */
    public function commandOutputShouldContain($message): void
    {
        Assertion::contains($this->commandTester->getDisplay(), $message);
    }

    /**
     * @Then /^Command should be successfully executed$/
     * @Then /^Command exit code should be (?P<code>\d+)$/
     */
    public function commandExitCodeShouldBe(int $code = 0): void
    {
        Assertion::eq($this->commandTester->getStatusCode(), $code);
    }
}
