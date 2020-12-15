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

$bot_api_key  = '1281492176:AAE-gMJyJ6PPr3WdTBDdpyLozmfSjnwccGw';
$bot_username = 'schedychanBot';
$hook_url     = 'https://redengo.com/bots/schedy/hook.php';
 
//"https://api.telegram.org/bot1281492176:AAE-gMJyJ6PPr3WdTBDdpyLozmfSjnwccGw/setWebhook?url=https://redengo.com/bots/sailorsapp/hook.php"
//"https://api.telegram.org/bot1232619568:AAFkibc9xxUxKej1vyC5VJ8morjrqM5db88/setWebhook?url=https://redengo.com/bots/sailorsapp/hook.php"

$admin_users = [
  
  480434336
//    123,
];
$commands_paths = [
     __DIR__ . '/Comandos/'  ,
	 


];

$mysql_credentials = [
     'host'     => 'localhost',
    'user'     => 'diego',
     'password' => 'NunoKuyen',
     'database' => 'schedy',
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

    // Logging (Error, Debug and Raw Updates)
    //Longman\TelegramBot\TelegramLog::initErrorLog(__DIR__ . "/{$bot_username}_error.log");
    //Longman\TelegramBot\TelegramLog::initDebugLog(__DIR__ . "/{$bot_username}_debug.log");
    //Longman\TelegramBot\TelegramLog::initUpdateLog(__DIR__ . "/{$bot_username}_update.log");

    // If you are using a custom Monolog instance for logging, use this instead of the above
    //Longman\TelegramBot\TelegramLog::initialize($your_external_monolog_instance);
/*
	TelegramLog::initialize(
		// Main logger that handles all 'error' and 'debug' logs.
		new Logger('telegram_bot', [
			(new StreamHandler(__DIR__ . "/logs/{$bot_username}_debug.log", Logger::DEBUG))->setFormatter(new LineFormatter(null, null, true)),
			(new StreamHandler(__DIR__ . "/logs/{$bot_username}_error.log", Logger::ERROR))->setFormatter(new LineFormatter(null, null, true)),
		]),
		// Updates logger for raw updates.
		new Logger('telegram_bot_updates', [
			(new StreamHandler(__DIR__ . "/logs/{$bot_username}_update.log", Logger::INFO))->setFormatter(new LineFormatter('%message%' . PHP_EOL)),
		])
	);
*/
	TelegramLog::initialize(
		// Main logger that handles all 'error' and 'debug' logs.
		new Logger('telegram_bot', [
		
			(new StreamHandler(__DIR__ . "/logs/{$bot_username}_error.log", Logger::ERROR))->setFormatter(new LineFormatter(null, null, true)),
		])
	);


    // Set custom Upload and Download paths
     $telegram->setDownloadPath(__DIR__ . '/Download');
     $telegram->setUploadPath(__DIR__ . '/Upload');

    // Here you can set some command specific parameters
    // e.g. Google geocode/timezone api key for /date command
    //$telegram->setCommandConfig('date', ['google_api_key' => 'your_google_api_key_here']);
/*
	$telegram->setCommandConfig('cleanup', [
	      // Define which tables should be cleaned.
	      'tables_to_clean' => [
	          'message',
	          'edited_message',
	      ],
	      // Define how old cleaned entries should be.
	      'clean_older_than' => [
	          'message'        => '3 days',
	          'edited_message' => '3 days',
	      ]
		  ]
	  );

*/
    // Botan.io integration
    //$telegram->enableBotan('your_botan_token');

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
