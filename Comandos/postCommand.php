<?php
namespace Longman\TelegramBot\Commands\UserCommands;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Funciones;

/**
 * Post command
 */
class PostCommand extends UserCommand
{
    protected $name = 'Post';
    protected $description = 'Posts indexed events to channels';
    protected $usage = '/post';
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
		
				 //  canal, userid, summary, description, fecha
		
					$des = "<b>que te parece </b><i><br/></i><br/>sailors.app<br><br/><br/>www.sailors.app";
					
					// 	-1001436283972 testear
					$des = Funciones::limpiar_html_bot($des);
					$dataChannel = [
						'chat_id' => -1001436283972,
						'text' => $des,
						'parse_mode' => 'HTML',
					];
					
					Request::sendMessage($dataChannel);
			
    }
}
