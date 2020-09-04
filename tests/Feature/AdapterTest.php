<?php

namespace Srmklive\PayPal\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;

class AdapterTest extends TestCase
{
    use MockClientClasses;

    protected static $access_token = '';

    /** @var \Srmklive\PayPal\Services\PayPal */
    protected $client;

    /** @test */
    public function it_throws_exception_if_invalid_credentials_are_provided()
    {
        $this->expectException(\RuntimeException::class);

        $this->client = new PayPalClient([]);
    }

    /** @test */
    public function it_can_take_valid_credentials_and_return_the_client_instance()
    {
        $this->client = new PayPalClient($this->getApiCredentials());

        $this->assertInstanceOf(PayPalClient::class, $this->client);
    }

    /** @test */
    public function it_throws_exception_if_invalid_credentials_are_provided_through_method()
    {
        $this->expectException(\RuntimeException::class);

        $this->client = new PayPalClient();
        $this->client->setApiCredentials([]);
    }

    /** @test */
    public function it_returns_the_client_instance_if_valid_credentials_are_provided_through_method()
    {
        $this->client = new PayPalClient();
        $this->client->setApiCredentials($this->getApiCredentials());

        $this->assertInstanceOf(PayPalClient::class, $this->client);
    }

    /** @test */
    public function it_throws_exception_if_invalid_currency_is_set()
    {
        $this->expectException(\RuntimeException::class);

        $this->client = new PayPalClient($this->getApiCredentials());
        $this->client->setCurrency('PKR');

        $this->assertNotEquals('PKR', $this->client->getCurrency());
    }

    /** @test */
    public function it_can_set_a_valid_currency()
    {
        $this->client = new PayPalClient($this->getApiCredentials());
        $this->client->setCurrency('EUR');

        $this->assertNotEmpty($this->client->getCurrency());
        $this->assertEquals('EUR', $this->client->getCurrency());
    }

    /** @test */
    public function it_can_set_valid_options_for_request()
    {
        $this->client = new PayPalClient($this->getApiCredentials());
        $this->client->setCurrency('EUR');

        $this->assertNotEmpty($this->client->getCurrency());
        $this->assertEquals('EUR', $this->client->getCurrency());
    }

    /** @test */
    public function it_can_get_access_token()
    {
        $this->client = new PayPalClient($this->getApiCredentials());
        $response = $this->client->getAccessToken();

        self::$access_token = $response['access_token'];

        $this->assertArrayHasKey('access_token', $response);
        $this->assertNotEmpty($response['access_token']);
    }

    /** @test */
    public function it_returns_error_if_invalid_credentials_are_used_to_get_access_token()
    {
        $this->client = new PayPalClient($this->getMockCredentials());
        $response = $this->client->getAccessToken();

        $this->assertArrayHasKey('type', $response);
        $this->assertEquals('error', $response['type']);
    }
}
