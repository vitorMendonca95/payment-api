<?php

namespace App\Jobs;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SendTransferenceNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 5;

    /**
     * Create a new job instance.
     *
     * @param string $userMail
     * @param array $transferenceDetails
     */
    public function __construct(
        private readonly string $userMail,
        private readonly array $transferenceDetails
    ) {
    }

    /**
     * @throws GuzzleException
     * @throws Throwable
     */
    public function handle(): void
    {
        $client = new Client();

        $url = config('services.notification_company.base_url') . config('services.notification_company.notify_path');

        try {
            $response = $client->get($url, [
                'json' => [
                    'user_id' => $this->userMail,
                    'transference_details' => $this->transferenceDetails,
                ],
            ]);

            if ($response->getStatusCode() != Response::HTTP_OK) {
                Log::error('Failed to send notification: ' . $response->getBody());
                throw new Exception('Failed to send notification');
            }
        } catch (Throwable $e) {
            Log::error('Notification service error: ' . $e->getMessage());

            throw $e;
        }
    }
}
