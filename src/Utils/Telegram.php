<?php

declare(strict_types=1);

namespace App\Utils;

use Exception;
use Telegram\Bot\Api;
use TelegramBot\Api\BotApi;

final class Telegram
{
    /**
     * 发送讯息，默认给群组发送
     */
    public static function send(string $messageText, int $chat_id = 0): void
    {
        if ($chat_id === 0) {
            $chat_id = $_ENV['telegram_chatid'];
        }
        if ($_ENV['enable_telegram'] === true) {
            if ($_ENV['use_new_telegram_bot'] === true) {
                // 发送给非群组时使用异步
                $async = ($chat_id !== $_ENV['telegram_chatid']);
                $bot = new Api($_ENV['telegram_token'], $async);
                $sendMessage = [
                    'chat_id' => $chat_id,
                    'text' => $messageText,
                    'parse_mode' => '',
                    'disable_web_page_preview' => false,
                    'reply_to_message_id' => null,
                    'reply_markup' => null,
                ];
                $bot->sendMessage($sendMessage);
            } else {
                $bot = new BotApi($_ENV['telegram_token']);
                $bot->sendMessage($chat_id, $messageText);
            }
        }
    }

    /**
     * 以 Markdown 格式发送讯息，默认给群组发送
     */
    public static function sendMarkdown(string $messageText, int $chat_id = 0): void
    {
        if ($chat_id === 0) {
            $chat_id = $_ENV['telegram_chatid'];
        }
        if ($_ENV['enable_telegram'] === true) {
            if ($_ENV['use_new_telegram_bot'] === true) {
                // 发送给非群组时使用异步
                $async = ($chat_id !== $_ENV['telegram_chatid']);
                $bot = new Api($_ENV['telegram_token'], $async);
                $sendMessage = [
                    'chat_id' => $chat_id,
                    'text' => $messageText,
                    'parse_mode' => 'Markdown',
                    'disable_web_page_preview' => false,
                    'reply_to_message_id' => null,
                    'reply_markup' => null,
                ];
                try {
                    $bot->sendMessage($sendMessage);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } else {
                $bot = new BotApi($_ENV['telegram_token']);
                try {
                    $bot->sendMessage($chat_id, $messageText, 'Markdown');
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }
}
