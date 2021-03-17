<?php
/**
 * README
 * This configuration file is intended to run the bot with the webhook method.
 * Uncommented parameters must be filled
 *
 * Please note that if you open this file with your browser you'll get the "Input is empty!" Exception.
 * This is a normal behaviour because this address has to be reached only by the Telegram servers.
 */

// Load composer
error_reporting(E_ALL);
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/Funciones/Funciones.php';
error_reporting(E_ERROR);

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Longman\TelegramBot\TelegramLog;

$bot_api_key  = '';
$bot_username = 'schedychanBot';
$hook_url     = 'https://redengo.com/bots/schedy/hook.php';


$admin_users = [
  
  480434336
//    123,
];
$commands_paths = [
     __DIR__ . '/Comandos/'  ,
	 


];

$mysql_credentials = [
     'host'     => 'localhost',
    'user'     => '',
     'password' => '',
     'database' => '',
 ];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Add commands paths containing your custom commands
    $telegram->addCommandsPaths($commands_paths);

    // Enable admin users
    $telegram->enableAdmins($admin_users);

    // Enable MySQL
    $telegram->enableMySql($mysql_credentials);

   
	TelegramLog::initialize(
		// Main logger that handles all 'error' and 'debug' logs.
		new Logger('telegram_bot', [
		
			(new StreamHandler(__DIR__ . "/logs/{$bot_username}_error.log", Logger::ERROR))->setFormatter(new LineFormatter(null, null, true)),
		])
	);


    // Set custom Upload and Download paths
     $telegram->setDownloadPath(__DIR__ . '/Download');
     $telegram->setUploadPath(__DIR__ . '/Upload');

  

    // Requests Limiter (tries to prevent reaching Telegram API limits)
    $telegram->enableLimiter();



    // Handle telegram webhook request
    $telegram->handle();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    echo $e;
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Silence is golden!
    // Uncomment this to catch log initialisation errors
    echo $e;
}
