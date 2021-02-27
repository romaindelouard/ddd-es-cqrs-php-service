<?php

declare(strict_types=1);

namespace Tests\Functional\Context;

use Behat\Gherkin\Node\TableNode;
use Doctrine\DBAL\Connection;

class DatabaseContext extends AbstractContext
{
    protected ?Connection $connection = null;

    /**
     * @BeforeScenario
     */
    public function initialize(): void
    {
        $this->connection = $this->getContainer()->get('doctrine.dbal.default_connection');
    }

    /**
     * @Given :tableName table is empty
     */
    public function theTableIsEmpty($tableName)
    {
        $this->connection->executeQuery(sprintf('TRUNCATE %s', $tableName));
    }

    /**
     * @Given the table :tableName contains the following rows:
     */
    public function theTableContainsTheFollowingRows($tableName, TableNode $table)
    {
        $this->theTableIsEmpty($tableName);
        foreach ($table->getHash() as $row) {
            array_walk(
                $row,
                function (&$value) {
                    if ('true' == $value) {
                        $value = true;
                    } else {
                        if ('false' == $value) {
                            $value = false;
                        }
                    }

                    return $value;
                },
                $row
            );
            $this->connection->insert($tableName, $row);
        }
    }

    private function getResults(array $row, $tableName)
    {
        $where = implode(
            ' AND ',
            array_map(
                function ($value, $key) {
                    return sprintf(
                        '%s %s %s',
                        $key,
                        'NULL' === $value ? 'IS' : '=',
                        'NULL' === $value ? 'NULL' : ':'.$key
                    );
                },
                array_values($row),
                array_keys($row)
            )
        );

        return $this->connection->fetchAll(
            sprintf(
                <<<SQL
SELECT * from %s WHERE %s
SQL,
                $tableName,
                $where
            ),
            array_filter(
                array_values($row),
                function ($value) {
                    return 'NULL' !== $value;
                }
            )
        );
    }

    /**
     * @Then the table :tableName should contain the following rows:
     */
    public function theTableShouldContainTheFollowingRows($tableName, TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $results = $this->getResults($row, $tableName);

            if (empty($results)) {
                throw new \Exception(sprintf('Could not find values %s', implode(',', $row)));
            }
        }
    }

    /**
     * @Then the table :tableName should not contain the following rows:
     */
    public function theTableShouldNotContainTheFollowingRows($tableName, TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $results = $this->getResults($row, $tableName);

            if (!empty($results)) {
                throw new \Exception(sprintf('Found values %s', implode(',', $row)));
            }
        }
    }
}
