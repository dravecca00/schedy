<?php
namespace Longman\TelegramBot\Commands\UserCommands;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Funciones;

/**
 * Postear command
 */
class PostearCommand extends UserCommand
{
    protected $name = 'Postear';
    protected $description = 'Posts indexed events to channels';
    protected $usage = '/postear';
    protected $version = '1.1.0';
    protected $private_only = true;
    public function execute()
    {
      $events = Funciones::get_eventos_apostear();
      foreach ($events as $event){
          //  canal, userid, summary, description, fecha
            $chan = intval($event['canal']);
            $des = Funciones::limpiar_html_bot($event['description']);
            $dataChannel = [	
              'chat_id' => $chan,
              'text' => $des,
              'parse_mode' => 'HTML'
            ];
            Request::sendMessage($dataChannel);
        }
                  
    }
}