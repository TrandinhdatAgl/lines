<?php

namespace Lines\Lines\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

/**
 * BotSendMessage
 */
class BotSendMessage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $accessToken,
        protected array $messages,
        protected string $userId,
    ) {
        $this->onConnection('database')->onQueue('lines');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $this->accessToken",
        ])->post('https://api.line.me/v2/bot/message/push', [
            'to' => $this->userId,
            'messages' => $this->messages,
        ]);
    }
}
