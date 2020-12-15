<?php
namespace Longman\TelegramBot;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Entities\InlineKeyboard;
use PDO;
use ICal\ICal;
error_reporting(E_ERROR);

class Funciones {
function SendPost($url,$params )
{
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);
return $result;
}


public static function dump($data, $chat_id = 480434336 )
{
    $dump = var_export($data, true);
    // Write the dump to the debug log, if enabled.
    TelegramLog::debug($dump);

    // Send the dump to the passed chat_id.
    if ($chat_id !== null || (property_exists(self::class, 'dump_chat_id') && $chat_id = self::$dump_chat_id)) {
        $result = Request::sendMessage([
            'chat_id'                  => $chat_id,
            'text'                     => $dump,
            'disable_web_page_preview' => true,
            'disable_notification'     => true,
        ]);

        if ($result->isOk()) {
            return $result;
        }

        TelegramLog::error('Var not dumped to chat_id %s; %s', $chat_id, $result->printError());
    }

    return Request::emptyResponse();
}


public static function debug_a_admins_php(   $quien, $msg )
{
$bot_api_key  = "";
$bot_username = '@Buchonbot';
$chatIds = array("480434336"); // Los destinatarios 
// if ( $quien == 'yo' ) $chatIds = array("662767623");
foreach ($chatIds as $chatId) {
	$data = array(   'chat_id' => $chatId,
	'text' => 'Debug '.$quien. '  '.var_export($msg,true) ,
	'parse_mode' => 'HTML' );
	 $response = file_get_contents("https://api.telegram.org/bot$bot_api_key/sendMessage?" . http_build_query($data) );
}
return ; 
}

	

public static function get_eventos( $id_usuario, $nro_dias ){
	$pdo = DB::getPdo();     if (! DB::isDbConnected()) {  return false;      }
	// tomar ical segun usuario
	$sql = "select url from  calendarios  where  userid =  ".$id_usuario;   
	$sth =   $pdo->prepare( $sql );
	$sth->execute();
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	if ( $result['url'] == '' ) return '';
	
	// hacer fetch del ical
	try {
		$ical = new ICal('ICal.ics', array(
			'defaultSpan'                 => 2,     // Default value
			'defaultTimeZone'             => 'UTC',
			'defaultWeekStart'            => 'MO',  // Default value
			'disableCharacterReplacement' => false, // Default value
			'filterDaysAfter'             => null,  // Default value
			'filterDaysBefore'            => true,  // Default value
			'skipRecurrence'              => false, // Default value
		));
		// $ical->initFile('ICal.ics');
		$ical->initUrl($result['url'], $username = null, $password = null, $userAgent = null);

	} catch (\Exception $e) {
		return  ('error');
		die($e);
	}
	$nro_eventos = $ical->eventCount;
	$intervalo = $nro_dias.' day';
	$events = $ical->eventsFromInterval("180 days");
	//$events = $ical->eventsFromRange('2020-07-01 12:00:00', '2020-08-01 17:00:00');
	$respuesta = '';

	foreach ($events as $event){
		$dtstart = $ical->iCalDateToDateTime($event->dtstart_array[3]);
		$comienza = $dtstart->modify('0 hours');
		$descripcion = $event->description;//Funciones::limpiar_cadena($event->description);
		$respuesta .= $event->summary . ' (' . $comienza->format('d-m-Y H:i') . ") UTC time not your local time\n\n";
	}
	// retornar array para mensaje
	if($respuesta===''){$respuesta = 'Did not find events';}
	return  ($respuesta);
	}


	public static function get_eventos_canal( $id_usuario){
		$pdo = DB::getPdo();     if (! DB::isDbConnected()) {  return false;      }
		// tomar ical segun usuario
		$sql = "select url from  calendarios  where  userid =  ".$id_usuario;   
		$sth =   $pdo->prepare( $sql );
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		if ( $result['url'] == '' ) return '';
		
		// hacer fetch del ical
		try {
			$ical = new ICal('ICal.ics', array(
				'defaultSpan'                 => 2,     // Default value
				'defaultTimeZone'             => 'UTC',
				'defaultWeekStart'            => 'MO',  // Default value
				'disableCharacterReplacement' => false, // Default value
				'filterDaysAfter'             => null,  // Default value
				'filterDaysBefore'            => true,  // Default value
				'skipRecurrence'              => false, // Default value
			));
			// $ical->initFile('ICal.ics');
			$ical->initUrl($result['url'], $username = null, $password = null, $userAgent = null);
	
		} catch (\Exception $e) {
			return  ('error');
			die($e);
		}
		$nro_eventos = $ical->eventCount;
		$intervalo = '1 day';
		$events = $ical->eventsFromInterval("$intervalo");
		//$events = $ical->eventsFromRange('2020-07-01 12:00:00', '2020-08-01 17:00:00');
		$eventos_array = array();
			
		foreach ($events as $event){
			$dtstart = $ical->iCalDateToDateTime($event->dtstart_array[3]);
			$comienza = $dtstart->modify('-3 hours');
			$respuesta .= $event->summary . ' (' . $comienza->format('d-m-Y H:i') . ")\n\n";
			$eventos_array[] = [
				"canal" => $event->summary,
				"comienza" => $comienza,
				"descripcion" => $event->printData()
			];

		}
		// retornar array para mensaje
		if($respuesta===''){$respuesta = 'No encontre eventos';}
		return  ($eventos_array);
		}


public static function guardar_calendario( $id_usuario, $url){
	$pdo = DB::getPdo();     if (! DB::isDbConnected()) {  return false;      }
	// tomar ical segun usuario
	$sql = "insert into calendarios (userid, url) VALUES (".$id_usuario.",'".$url."')";   
	$sth = $pdo->prepare( $sql );
	$sth->execute();
	
	return  ("saved... $sth->lastInsertId");
	}
	
public static function encontrar_canal($canalTit){
	$pdo = DB::getPdo();     if (! DB::isDbConnected()) {  return false;      }
	$canal = substr($canalTit,1);
	// tomar ical segun usuario
	$sql = "select id from chat  where ( type = 'channel' and title =  '".$canal."' ) order by updated_at DESC LIMIT 1 ";   
	$sth =   $pdo->prepare( $sql );
	$sth->execute();
	$result = $sth->fetch(PDO::FETCH_ASSOC);

	return ($result['id']);
	}

public static function sincronizar_calendario($id_usuario){
		$pdo = DB::getPdo();     if (! DB::isDbConnected()) {  return false;      }
		// tomar ical segun usuario
		$sql = "select url from  calendarios  where  userid =  ".$id_usuario." ORDER BY id DESC LIMIT 1";   
		$sth =   $pdo->prepare( $sql );
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		if ( $result['url'] == '' ){return 'send valid ical url';}
		
		try {
			$ical = new ICal('ICal.ics', array(
				'defaultSpan'                 => 2,     // Default value
				'defaultTimeZone'             => 'UTC',
				'defaultWeekStart'            => 'MO',  // Default value
				'disableCharacterReplacement' => false, // Default value
				'filterDaysAfter'             => null,  // Default value
				'filterDaysBefore'            => true,  // Default value
				'skipRecurrence'              => false, // Default value
			));
			// $ical->initFile('ICal.ics');
			$ical->initUrl($result['url'], $username = null, $password = null, $userAgent = null);
	
		} catch (\Exception $e) {
			return  ('error, please send me a valid ical link -'.$e);
		}
		$nro_eventos = $ical->eventCount;
		$intervalo = '180 day';
		$events = $ical->eventsFromInterval("$intervalo");
		$eventos_array = array();
		
		$sql = "delete from calendarios_eventos where userid =  ".$id_usuario. " ";   
		$sth =  $pdo->prepare( $sql );
		$sth->execute();

		$values = array();
	

		foreach ($events as $event){
				$dtstart = $ical->iCalDateToDateTime($event->dtstart_array[3]);
				//$comienza = $dtstart->modify('-3 hours');
				$comienza = $event->dtstart_array[2];
				$canales = Funciones::extraer_canales_texto($event->summary);
				$canalid = Funciones::encontrar_canal($canales); // extrae formato @nombrecanal y luego encuentra canal
				$fecha = $comienza; //$comienza->format('Y-m-d H:i:s');//"2020-01-01 19:59:00"
				$description = html_entity_decode($event->description);

				///anti truchada
				//Funciones::dump($description);
				
				$ad = json_decode(file_get_contents("https://api.telegram.org/bot1281492176:AAE-gMJyJ6PPr3WdTBDdpyLozmfSjnwccGw/getChatAdministrators?chat_id=$canalid"));
				$arr = $ad->result;
				foreach($arr as $r){
					if($r->status =='creator' and $r->user->id == $id_usuario){
						$values [] =  "($canalid, $id_usuario,'".$event->summary."','".$description."','".$fecha."')";
						}
				}
				
			}
			if(!empty($values)){
				$sqlvalues = implode(",",$values);
				$sql = "insert into calendarios_eventos (canal, userid, summary, description, fecha) VALUES $sqlvalues"; 
				$sth = $pdo->prepare( $sql );
				$sth->execute();
			}
			
	return('done');
	}

public static function extraer_canales_texto($sentence){
		if (preg_match("/@+[\w]+/", $sentence, $match)) {
			$res = $match[0];
		}
			return($res);
	}

	
public static function get_eventos_apostear(){
	
		$pdo = DB::getPdo();     if (! DB::isDbConnected()) {  return false; }
		$utc = time();// utc actual
		$span = 4 * 60; //distancia 5 minutos pero el cron cada 5
		// cambie a 4 porque disparo y diez algo que era y 15
		$datemin = $utc - $span;
		$datemax = $utc + $span;

		$sql = "select * from calendarios_eventos where (fecha > $datemin AND fecha < $datemax AND enviado is null) ";   
		$sth =   $pdo->prepare( $sql );
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$response = false;
		if($result){
			foreach ($result as $event){
				//canal, userid, summary, description, fecha
				$sql = "update calendarios_eventos SET enviado=1 where id=".$event['id'];   
				$sth = $pdo->prepare( $sql );
				$sth->execute();
				}
			$response = $result;	
		}
	return($response);
	}

public static function limpiar_html_bot($html){
	//$html = htmlspecialchars($html);
	$result = str_replace(
		array("<br>", "<br/>"), 
		array(" \n ", " \n "), 
		$html
	  );
	  $result = strip_tags($result, '<a><b><strong><i><em><u><ins><s><strike><del><code><pre>');
	  return $result;
}


}//fin clase

?>
