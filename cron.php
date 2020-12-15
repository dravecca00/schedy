<?php
/**
 * README
 * This configuration file is intended to run a list of commands with crontab.
 * Uncommented parameters must be filled
 */

// Your command(s) to run, pass it just like in a message (arguments supported)
$commands = [
    '/postear'
];

// Load composer
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/Funciones/Funciones.php';
// Add you bot's API key and name
$bot_api_key  = '1281492176:AAE-gMJyJ6PPr3WdTBDdpyLozmfSjnwccGw';
$bot_username = 'schedychanBot';

// Define all IDs of admin users in this array (leave as empty array if not used)
$admin_users = [
  480434336
];

// Define all paths for your custom commands in this array (leave as empty array if not used)
$commands_paths = [
    __DIR__ . '/Comandos/',
];

// Enter your MySQL database credentials
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
    // https://github.com/php-telegram-bot/core/blob/master/doc/01-utils.md#logging
    //
    // (this example requires Monolog: composer require monolog/monolog)
    //Longman\TelegramBot\TelegramLog::initialize(
    //    new Monolog\Logger('telegram_bot', [
    //        (new Monolog\Handler\StreamHandler(__DIR__ . "/{$bot_username}_debug.log", Monolog\Logger::DEBUG))->setFormatter(new Monolog\Formatter\LineFormatter(null, null, true)),
    //        (new Monolog\Handler\StreamHandler(__DIR__ . "/{$bot_username}_error.log", Monolog\Logger::ERROR))->setFormatter(new Monolog\Formatter\LineFormatter(null, null, true)),
    //    ]),
    //    new Monolog\Logger('telegram_bot_updates', [
    //        (new Monolog\Handler\StreamHandler(__DIR__ . "/{$bot_username}_update.log", Monolog\Logger::INFO))->setFormatter(new Monolog\Formatter\LineFormatter('%message%' . PHP_EOL)),
    //    ])
    //);

    // Set custom Upload and Download paths
    //$telegram->setDownloadPath(__DIR__ . '/Download');
    //$telegram->setUploadPath(__DIR__ . '/Upload');

    // Here you can set some command specific parameters,
    // e.g. Google geocode/timezone api key for /date command:
    //$telegram->setCommandConfig('date', ['google_api_key' => 'your_google_api_key_here']);

    // Requests Limiter (tries to prevent reaching Telegram API limits)
    $telegram->enableLimiter();

    // Run user selected commands
    $telegram->runCommands($commands);

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    Funciones::dump($e);
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Silence is golden!
    // Uncomment this to catch log initialisation errors
    //echo $e;
}
