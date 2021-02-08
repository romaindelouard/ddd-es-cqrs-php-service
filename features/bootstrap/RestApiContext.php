<?php

namespace Tests\Functional\BehatContext;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class RestApiContext implements Context
{
    protected $lastResponse;
    protected KernelInterface $kernel;
    protected Response $response;
    private ?string $token = null;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When I send a :method request to :path with JSON params:
     */
    public function iSendARequestToWithJsonParams($method, $path, PyStringNode $parameters)
    {
        $request = Request::create(
          $path,
          $method,
          (array) json_decode($parameters->getRaw()),
          [],
          [],
          $this->headers()
        );
        $this->response = $this->kernel->handle($request);
    }

    /**
     * @Then I should get :statusCode HTTP response status code
     */
    public function iShouldGetHttpResponseCode($statusCode)
    {
        var_dump($this->response->getContent());
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
