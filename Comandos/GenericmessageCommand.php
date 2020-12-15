<?php
namespace Longman\TelegramBot\Commands\SystemCommands;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Funciones;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;
class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.1.0';
    protected $need_mysql = true;
    public function executeNoDb()
    {
        // Do nothing
        return Request::emptyResponse();
    }
    public function execute()
    {
        //If a conversation is busy, execute the conversation command after handling the message
        $conversation = new Conversation(
            $this->getMessage()->getFrom()->getId(),
            $this->getMessage()->getChat()->getId()
        );
		$message = $this->getMessage();

        //You can use $command as param
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $command = $message->getCommand();
	    $data = [
            'chat_id' => $chat_id,
            'text'    => 'GM' . $command . ' not found.. :(',
        ];
		
			
		
		
		if ($message->getPhoto() != null) {
			//return $this->telegram->executeCommand('precios');
	    }
        if ($conversation->exists() && ($command = $conversation->getCommand())) {
            return $this->telegram->executeCommand($command);
		}
		
		$texto_ingresado= $message->getText();	
		
		if ( trim($texto_ingresado) == '' )  return Request::emptyResponse();
		
		preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $texto_ingresado, $match);
		$url =$match[0][0];

		//si el texto comienza con http guardarlo como calendario
		if(empty($url)){
			$response = 'did not find any url';
		} else {
			$response = Funciones::guardar_calendario( $user_id, $url );
			//$response = 'url = '.$url;
		}
	
		$data['text']= $response;
		Request::sendMessage($data); 
		
    }
}
