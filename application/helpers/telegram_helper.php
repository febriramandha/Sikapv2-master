<?php 

if ( ! function_exists('telegram_send'))
{
    function telegram_send(string $chat_id, string $message, array $option = [])
    {
		$telegram_token = "5046901079:AAHwGUb5cNR5WWRpIRcJp0b6rEujEh8ZDSk";
        $telegram_uri_path = "https://api.telegram.org/bot".$telegram_token;
        $payload = array(
            "chat_id" => $chat_id,
            "text" => $message,
            "parse_mode" => "html"
        );

        $payload = json_encode(array_merge($payload, $option));

        $ch = curl_init( $telegram_uri_path . "/sendmessage" );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);
    }
}

?>