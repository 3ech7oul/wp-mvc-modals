<?php

namespace FormsPlugin\Services;

use React\EventLoop\Factory;
use \unreal4u\TelegramAPI\HttpClientRequestHandler;
use \unreal4u\TelegramAPI\Abstracts\TraversableCustomType;
use unreal4u\TelegramAPI\TgLog;
use unreal4u\TelegramAPI\Telegram\Methods\GetUpdates;
use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;

class TelegramService
{
    private $botToken;

    private $allowUsers = [];

    private $chatIds = [];

    public function __construct()
    {
        $pluginOptions =  \get_option('plugin_form');
        $this->botToken = $pluginOptions['plugin_form_bot_token'];
        $allowUsersString = $pluginOptions['plugin_form_username'];
        $this->allowUsers = explode(",",$allowUsersString);
    }

    private function getUpdates()
    {
        $loop = Factory::create();
        $tgLog = new TgLog($this->botToken, new HttpClientRequestHandler($loop));
        $getUpdates = new GetUpdates();

        $updatePromise = $tgLog->performApiRequest($getUpdates);
        $updatePromise->then(
            function (TraversableCustomType $updatesArray) {
                foreach ($updatesArray as $update) {
                    if (in_array($update->message->chat->username, $this->allowUsers)) {
                        if (!in_array($update->message->chat->id, $this->chatIds)) {
                            array_push($this->chatIds, $update->message->chat->id);
                        }
                    }
                }
            },
            function (\Exception $exception) {
                echo 'Exception ' . get_class($exception) . ' caught, message: ' . $exception->getMessage();
            }
        );
        $loop->run();
    }

    public function sendMessage($message)
    {
        $loop = Factory::create();
        $tgLog = new TgLog($this->botToken, new HttpClientRequestHandler($loop));
        $this->getUpdates();

        foreach ($this->chatIds as $chatId) {
            var_dump($chatId);
            $sendMessage = new SendMessage();
            $sendMessage->parse_mode = 'HTML';
            $sendMessage->chat_id = $chatId;
            $sendMessage->text = $message;
            $promise = $tgLog->performApiRequest($sendMessage);
            $promise->then(
                function ($response) {
                    echo '<pre>';
                   // var_dump($response);
                    echo '</pre>';
                },
                function (\Exception $exception) {
                    // Onoes, an exception occurred...
                    echo 'Exception ' . get_class($exception) . ' caught, message: ' . $exception->getMessage();
                }
            );
        }
        $loop->run();
        return true;
    }

}