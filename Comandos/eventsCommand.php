<?php
namespace Longman\TelegramBot\Commands\UserCommands;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Funciones;
use ICal\ICal;

/**
 * Eventos command
 */
class EventsCommand extends UserCommand
{
    protected $name = 'Events';
    protected $description = 'Shows calendar events';
    protected $usage = '/events';
    protected $version = '1.1.0';
    protected $private_only = true;
    public function execute()
    {

        if ($this->getCallbackQuery() !== null) {
			 //$message =  $update->getMessage();
             $message  = $this->getCallbackQuery()->getMessage();
			 $chat    =$this->getCallbackQuery()->getMessage()->getChat();
			 $user    = $chat;
			 $chat_id =  $this->getCallbackQuery()->getMessage()->getChat()->getId();
			 $user_id = $chat_id;
			 $text    = trim($message->getText(true));
		}
		else
		{
			$message = $this->getMessage() ?: $this->getEditedMessage();
			$chat    = $message->getChat();
			$user    = $message->getFrom();
			$chat_id = $chat->getId();
			$user_id = $user->getId();
			$text    = trim($message->getText(true));
        }
		$data = [
					'chat_id'    => $chat_id,
					'user_id' => $user_id,					
					'parse_mode' => 'HTML',
					'disable_web_page_preview'=>false,
				];
		
		$evento = Funciones::get_eventos($user_id,180);
		// funciona bien pero ahora hago otra funcion que devuelve array de objetos
		$data['text'] = "$evento";
		Request::sendMessage($data);

    }
}
