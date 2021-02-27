<?php

declare(strict_types=1);

namespace Tests\Functional\Context;

use Behat\Gherkin\Node\PyStringNode;
use Coduo\PHPMatcher\PHPMatcher;
use Seld\JsonLint\JsonParser;

class JsonRpcApiContext extends AbstractContext
{
    /**
     * @var \stdClass|string
     */
    protected $lastJsonRpcResponse;

    /**
     * @Given I define a user agent for JSON-RPC HTTP client with :userAgent
     */
    public function iDefineAUserAgentForJsonRpcHttpClientWith(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @When I call JSON-RPC method :method
     * @When I call JSON-RPC method :method with params:
     *
     * @param string $method
     *
     * @throws \Exception
     */
    public function iCallJsonRpcMethodWithParams($method, PyStringNode $params = null)
    {
        $request = [
            'id' => uniqid('', true),
            'jsonrpc' => '2.0',
            'method' => $method,
        ];

        if (null !== $params) {
            $request['params'] = $this->parseJson($params->getRaw());
        }

        $this->call($request);
    }

    /**
     * @Then I should get result containing :property property
     * @Then I should get result containing :property property set exactly to :value
     * @Then I should get result containing :property property set exactly to:
     *
     * @param string $property
     * @param string|PyStringNode $value
     *
     * @throws \Exception
     */
    public function iShouldGetResultContainingProperty($property, $value = null)
    {
        $this->checkResponseHasResult();
        $this->checkObjectHasProperty($this->lastJsonRpcResponse->result, $property);

        if (null !== $value) {
            $responseValue = $this->lastJsonRpcResponse->result->{$property};
            if (!$this->isEqual($responseValue, $value)) {
                throw new \Exception(sprintf("Last response : %s\nExpected %s set to %s, got %s", $this->stringify($this->lastJsonRpcResponse), $property, $value instanceof PyStringNode ? $this->stringify(json_decode($value->getRaw())) : $value, $this->stringify($responseValue)));
            }
        }
    }

    /**
     * @Then I should get result containing :property property set to boolean :value
     *
     * @param string $property
     * @param string|PyStringNode $value
     *
     * @throws \Exception
     */
    public function iShouldGetResultContainingPropertySetToBoolean($property, $value)
    {
        $this->checkResponseHasResult();
        $this->checkObjectHasProperty($this->lastJsonRpcResponse->result, $property);

        $responseValue = $this->lastJsonRpcResponse->result->{$property};
        if (!$this->isBooleanEqual($responseValue, $value)) {
            throw new \Exception(sprintf("Last response : %s\nExpected %s set to boolean %s, got %s", $this->stringify($this->lastJsonRpcResponse), $property, $value, $this->stringify($responseValue)));
        }
    }

    /**
     * @Then I should get result containing null :property property
     *
     * @param string $property
     *
     * @throws \Exception
     */
    public function iShouldGetResultContainingNullProperty($property)
    {
        $this->checkResponseHasResult();
        $this->checkObjectHasProperty($this->lastJsonRpcResponse->result, $property);

        $responseValue = $this->lastJsonRpcResponse->result->{$property};
        if (!is_null($responseValue)) {
            throw new \Exception(sprintf("Last response : %s\nExpected %s to be null, got %s", $this->stringify($this->lastJsonRpcResponse), $property, $this->stringify($responseValue)));
        }
    }

    /**
     * @Then I should get result containing :property property containing all of the following:
     *
     * @param string $property
     */
    public function iShouldGetResultContainingPropertyContainingAllOfTheFollowing($property, PyStringNode $values)
    {
        $this->checkResponseHasResult();
        $this->checkObjectHasProperty($this->lastJsonRpcResponse->result, $property);
        $this->checkObjectContains($this->lastJsonRpcResponse->result->{$property}, $values);
    }

    /**
     * @Then I should get the following exact result:
     * @Then I should get the exact result :value
     *
     * @param PyStringNode|string $value
     *
     * @throws \Exception
     */
    public function iShouldGetExactResult($value)
    {
        $this->checkResponseHasResult();
        if (!$this->isEqual($this->lastJsonRpcResponse->result, $value)) {
            throw new \Exception(sprintf("Last response : %s\nExpected exact result %s, got %s", $this->stringify($this->lastJsonRpcResponse), $value instanceof PyStringNode ? $this->stringify(json_decode($value->getRaw())) : $value, $this->stringify($this->lastJsonRpcResponse->result)));
        }
    }

    /**
     * @Then the JSON response should match:
     *
     * @param PyStringNode|$value
     *
     * @throws \Exception
     */
    public function iShouldMatchResult($value)
    {
        $this->checkResponseHasResult();
        if (!$this->isMatching($this->lastJsonRpcResponse->result, $value)) {
            throw new \Exception(sprintf("Matching response error : %s.\nLast response result : %s", $this->stringify($this->lastMatcherError), $this->stringify($this->lastJsonRpcResponse->result)));
        }
    }

    /**
     * @Then I should get boolean result :value
     *
     * @param PyStringNode|string $value
     *
     * @throws \Exception
     */
    public function iShouldGetBooleanResult($value)
    {
        $this->checkResponseHasResult();
        if (!$this->isBooleanEqual($this->lastJsonRpcResponse->result, $value)) {
            throw new \Exception(sprintf("Last response : %s\nExpected boolean result %s, got %s", $this->stringify($this->lastJsonRpcResponse), $value, $this->stringify($this->lastJsonRpcResponse->result)));
        }
    }

    /**
     * @Then I should get null result
     *
     * @throws \Exception
     */
    public function iShouldGetNullResult()
    {
        $this->checkResponseHasResult();
        if (!is_null($this->lastJsonRpcResponse->result)) {
            throw new \Exception(sprintf("Last response : %s\nExpected null result, got %s", $this->stringify($this->lastJsonRpcResponse), $this->stringify($this->lastJsonRpcResponse->result)));
        }
    }

    /**
     * @Then I should get result containing all of the following:
     */
    public function iShouldGetResultContainingAllOfTheFollowing(PyStringNode $values)
    {
        $this->checkResponseHasResult();
        $this->checkObjectContains($this->lastJsonRpcResponse->result, $values);
    }

    /**
     * @Then I should get result containing collection property :property:
     * @Then I should get result containing collection property :property with items containing all of the following:
     *
     * @param string $property
     *
     * @throws \Exception
     */
    public function iShouldGetResultContainingCollectionPropertyWithItemsContainingAllOfTheFollowing(
        $property,
        PyStringNode $values = null
    ) {
        $this->checkResponseHasResult();
        $this->checkObjectHasProperty($this->lastJsonRpcResponse->result, $property);

        if (null !== $values) {
            foreach ($this->lastJsonRpcResponse->result->{$property} as $resultItem) {
                $this->checkObjectContains($resultItem, $values);
            }
        } else {
            if (!is_array($this->lastJsonRpcResponse->result->{$property})) {
                throw new \Exception(sprintf('Expected property %s to be a collection, got %s', $property, $this->stringify($this->lastJsonRpcResponse->result->{$property})));
            }
        }
    }

    /**
     * @Then An item of the :property collection should contain all of the following:
     *
     * @param string $property
     *
     * @throws \Exception
     */
    public function anItemOfTheCollectionShouldContainAllOfTheFollowing($property, PyStringNode $values)
    {
        $this->iShouldGetResultContainingCollectionPropertyWithItemsContainingAllOfTheFollowing($property);

        foreach ($this->lastJsonRpcResponse->result->{$property} as $resultItem) {
            try {
                $this->checkObjectContains($resultItem, $values);

                return;
            } catch (\Exception $e) {
                continue;
            }
        }

        throw new \Exception(sprintf('Could not find an item containing %s in %s', $this->stringify(json_decode($values->getRaw())), $this->stringify($this->lastJsonRpcResponse->result->{$property})));
    }

    /**
     * @Then The :property collection should contain :count items
     *
     * @param string $property
     * @param string $count
     *
     * @throws \Exception
     */
    public function theCollectionShouldContainItems($property, $count)
    {
        $nbItems = count($this->lastJsonRpcResponse->result->{$property});

        if ($nbItems !== intval($count)) {
            throw new \Exception(sprintf('Expected %s items in collection %s, got %d', $count, $property, $nbItems));
        }
    }

    /**
     * @Then I should get an error with message :message
     * @Then I should get an error with message:
     *
     * @param string $message
     *
     * @throws \Exception
     */
    public function iShouldGetAnErrorWithMessage($message)
    {
        $this->checkResponseHasError();
        $message = $message instanceof PyStringNode ? $message->getRaw() : $message;

        if ($this->lastJsonRpcResponse->error->message !== $message) {
            throw new \Exception(sprintf("Expected %s\nGot %s", $message, $this->lastJsonRpcResponse->error->message));
        }
    }

    /**
     * @throws \Exception
     */
    private function checkResponseHasError()
    {
        $this->checkRootKeyExistence('error');
    }

    /**
     * @throws \Exception
     */
    private function checkResponseHasResult()
    {
        $this->checkRootKeyExistence('result');
    }

    /**
     * @param string $rootKey
     *
     * @throws \Exception
     */
    private function checkRootKeyExistence($rootKey)
    {
        if (!property_exists($this->lastJsonRpcResponse, $rootKey)) {
            throw new \Exception(sprintf("Last response : %s\nNo %s in response", $this->stringify($this->lastJsonRpcResponse), $rootKey));
        }
    }

    /**
     * @param string $property
     *
     * @throws \Exception
     */
    private function checkObjectHasProperty(\stdClass $object, $property)
    {
        if (!property_exists($object, $property)) {
            throw new \Exception(sprintf('No property %s in object %s', $property, $this->stringify($object)));
        }
    }

    /**
     * @throws \Exception
     */
    private function checkObjectContains(\stdClass $object, PyStringNode $values)
    {
        $expected = json_decode($values->getRaw(), true);

        foreach ($expected as $property => $value) {
            $this->checkObjectHasProperty($object, $property);
            if (!$this->isEqual($object->{$property}, $value)) {
                throw new \Exception(sprintf('Expected property %s set to %s, got %s', $property, $this->stringify($value), $this->stringify($object->{$property})));
            }
        }
    }

    /**
     * @param \stdClass|string $responseValue
     * @param string|PyStringNode $expectedValue
     *
     * @return bool
     */
    private function isEqual($responseValue, $expectedValue)
    {
        if ($expectedValue instanceof PyStringNode) {
            return $this->stringify(json_decode($expectedValue->getRaw())) === $this->stringify($responseValue);
        }

        return $expectedValue == $responseValue;
    }

    /**
     * Check if two values are matching.
     *
     * @param \stdClass|string $responseValue
     * @param string|PyStringNode $expectedValue
     *
     * @return bool
     */
    private function isMatching($responseValue, $expectedValue)
    {
        $matcher = new PHPMatcher();

        if ($expectedValue instanceof PyStringNode) {
            $expectedValue = $this->stringify($expectedValue->getRaw());
            $responseValue = $this->stringify(json_encode($responseValue));
        }

        $result = $matcher->match($responseValue, $expectedValue);
        $this->lastMatcherError = $matcher->error();

        return $result;
    }

    /**
     * @param mixed $responseValue
     * @param string $expectedValue
     *
     * @return bool
     */
    private function isBooleanEqual($responseValue, $expectedValue)
    {
        return $responseValue === $this->boolify($expectedValue);
    }

    /**
     * JSON-encodes an array or an object, returns the string otherwise.
     *
     * @param string|array|object $value
     *
     * @return string
     */
    private function stringify($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return is_object($value) || is_array($value) ? json_encode($value) : $value;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private function boolify($value)
    {
        return 'true' === $value;
    }

    /**
     * Parses the given JSON string.
     *
     * @param string $jsonString an application/json type
     *
     * @return mixed
     */
    private function parseJson($jsonString)
    {
        $parser = new JsonParser();

        return $parser->parse($jsonString);
    }

    /**
     * Makes the JSON-RPC call.
     */
    private function call(array $request)
    {
        $client = $this->getContainer()->get('test.client');

        $parametersServer = [
          'CONTENT_TYPE' => 'application/json',
          'HTTP_ACCEPT' => 'application/json',
      ];

        if (!empty($this->userAgent)) {
            $parametersServer = array_merge($parametersServer, ['HTTP_USER_AGENT' => $this->userAgent]);
        }

        $client->request('POST', '/json-rpc', [], [], $parametersServer, json_encode($request));

        $this->parseResponseContent($client->getResponse()->getContent());
    }

    /**
     * @param string $content
     *
     * @throws \Exception
     */
    private function parseResponseContent($content)
    {
        try {
            $this->lastJsonRpcResponse = $this->parseJson($content);
        } catch (\Exception $e) {
            echo substr($content, 0, 1000);

            throw new \Exception('Response body could not be parsed as JSON.', 0, $e);
        }
    }
}
