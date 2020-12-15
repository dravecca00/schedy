<?php
$canalid = -1001464533975;
$id_usuario = 480434336;
$ad = json_decode(file_get_contents("https://api.telegram.org/bot1281492176:AAE-gMJyJ6PPr3WdTBDdpyLozmfSjnwccGw/getChatAdministrators?chat_id=$canalid"));
echo("<pre>");
var_dump($ad->result);
$arr = $ad->result;
				foreach($arr as $r){
					if($r->status =='creator' and $r->user->id==$id_usuario){
                        echo ('real');
												}
				}