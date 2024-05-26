<?php

namespace Tests\Unit\Jobs;

use App\Jobs\SendTransferenceNotification;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;
use Throwable;

class SendTransferenceNotificationTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @throws Throwable
     * @throws GuzzleException
     */
    public function testHandleSuccess()
    {
        $userMail = fake()->freeEmail();
        $transferenceDetails = ['amount' => fake()->numberBetween(100, 200)];

        $clientMock = Mockery::mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->once()
                ->andReturn(new Response(200, [], 'OK'));
        });

        $job = new SendTransferenceNotification($userMail, $transferenceDetails);

        try {
            $job->handle($clientMock);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Exception was not expected: ' . $e->getMessage());
        }
    }

    /**
     * @throws Throwable
     * @throws GuzzleException
     */
    public function testHandleFailure()
    {
        $userMail = fake()->freeEmail();
        $transferenceDetails = ['amount' => fake()->numberBetween(100, 200)];

        $clientMock = Mockery::mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->once()
                ->andThrow(new RequestException('Error', new Request('GET', 'test')));
        });

        Log::shouldReceive('error')
            ->once();

        $job = new SendTransferenceNotification($userMail, $transferenceDetails);

        $this->expectException(RequestException::class);

        $job->handle($clientMock);
    }

    /**
     * @throws Throwable
     * @throws GuzzleException
     */
    public function testHandleInvalidResponse()
    {
        $userMail = fake()->freeEmail();
        $transferenceDetails = ['amount' => fake()->numberBetween(100, 200)];

        $clientMock = Mockery::mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->once()
                ->andReturn(new Response(500, [], 'Internal Server Error'));
        });

        Log::shouldReceive('error')
            ->twice();

        $job = new SendTransferenceNotification($userMail, $transferenceDetails);

        $this->expectException(Exception::class);

        $job->handle($clientMock);
    }
}
