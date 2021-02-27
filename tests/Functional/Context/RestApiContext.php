<?php

declare(strict_types=1);

namespace Tests\Functional\Context;

use Assert\Assertion;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestApiContext extends AbstractContext
{
    protected $lastResponse;
    protected Response $response;
    private ?string $token = null;

    /**
     * @When I send a :method request to :path
     * @When I send a :method request to :path with JSON params:
     */
    public function iSendARequestToWithJsonParams($method, $path, PyStringNode $parameters = null)
    {
        $request = Request::create(
          $path,
          $method,
          $parameters ? json_decode($parameters->getRaw(), true) : [],
          [],
          [],
          $this->headers()
        );
        $this->response = $this->getKernel()->handle($request);
    }

    /**
     * @Then I should get :statusCode HTTP response status code
     */
    public function iShouldGetHttpResponseCode($statusCode)
    {
        $contentString = $this->response->getContent();
        $content = json_decode($contentString, true);
        $this->token = $content && isset($content['token']) ? $content['token'] : null;
        Assertion::eq($statusCode, $this->response->getStatusCode());
    }

    /**
     * @Then I should get a HTTP response with error message:
     */
    public function iShouldGetAHttpResponseWithErrorMessage(PyStringNode $message)
    {
        $contentString = $this->response->getContent();
        $content = json_decode($contentString, true);
        $expectedErrorMessage = json_decode($message->getRaw(), true);
        if (!isset($content['error'])) {
            throw new \UnexpectedValueException('The response has no error message.');
        }
        try {
            Assertion::eq($content['error'], $expectedErrorMessage);
        } catch (\Exception $e) {
            var_dump($content);
            throw $e;
        }
    }

    private function headers(): array
    {
        $headers = [
            'CONTENT_TYPE' => 'application/json',
        ];

        if ($this->token) {
            $headers['HTTP_Authorization'] = 'Bearer '.$this->token;
        }

        return $headers;
    }
}
