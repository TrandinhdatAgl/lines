<?php

namespace Lines\Lines\Supports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Lines\Lines\Jobs\BotSendMessage;

/**
 * LineFacade
 */
class LineFacade
{
    /**
     * sendBotMessage
     *
     * @param array          $messages
     * @param string         $lineId
     * @param integer|string $golfCourseId
     * @param Model          $user
     * @param boolean        $isQueue
     * @return boolean
     */
    public function sendBotMessage(array $messages, string $lineId, int|string $golfCourseId, Model $user, bool $isQueue = false): bool
    {
        try {
            foreach ($messages as $key => $message) {
                if (is_null($message)) {
                    unset($messages[$key]);
                    continue;
                }
                if ($message['type'] == 'text') {
                    $replacements = [
                        '{last_name}' => $user->last_name,
                        '{first_name}' => $user->first_name,
                        '{sex}' => $user->sex,
                        '{birthday}' => $user->birthday,
                        '{phone_number_1}' => $user->phone_number_1,
                        '{phone_number_2}' => $user->phone_number_2,
                        '{zipcode}' => $user->zipcode,
                        '{prefecture}' => $user->prefecture,
                        '{address}' => $user->address,
                        '{email}' => $user->email,
                        '{nickname}' => $user->nickname,
                    ];

                    $messages[$key]['text'] = str_replace(array_keys($replacements), array_values($replacements), $message['text']);
                }
            }

            if ($isQueue) {
                BotSendMessage::dispatch(config('line.accounts')[$golfCourseId]['access_token'], $messages, $lineId);

                return true;
            }

            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer " . config('line.accounts')[$golfCourseId]['access_token'],
            ])->post('https://api.line.me/v2/bot/message/push', [
                'to' => $lineId,
                'messages' => $messages,
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
