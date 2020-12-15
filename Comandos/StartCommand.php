<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Funciones;
use Translation\Translation;

/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {

        $message = $this->getMessage();
        $from  = $message->getFrom();
        $n = $from->getFirstName();
        $l = $from->getLanguageCode();

        $chat_id = $message->getChat()->getId();
		$message = $this->getMessage();
		$chat    = $message->getChat();
		$user    = $message->getFrom();
		
		$chat_id = $chat->getId();
		$user_id = $user->getId();
		$text    = trim($message->getText(true));
        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,

        ];
        
		$data['parse_mode'] = 'HTML';
		$data['disable_web_page_preview']=false;
        $data['text'] = '<strong>Setup</strong>'.PHP_EOL.
        '1. Paste ical link'.PHP_EOL.
        '2. Add bot to channel, then promote to Admin'.PHP_EOL.
        '3. Send one normal message to channel'.PHP_EOL. 
        '<strong>Normal usage</strong>'.PHP_EOL.
        '-- Events on your calendar must have @channelName on title'.PHP_EOL.
        '-- No html on description.'.PHP_EOL. 
        '-- After calendar change use /sync to update.'.PHP_EOL;

		Request::sendMessage($data);

    }
	
	
}
