<?php

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
