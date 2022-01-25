<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Telegramsocket extends MY_Controller {
    public function __construct() {
		parent::__construct ();
        $this->user_id = $this->session->userdata('tpp_user_id');
        $this->dept_id = $this->session->userdata('tpp_dept_id');
        $this->username = $this->session->userdata('tpp_username');
        $this->telegram_token = "5046901079:AAHwGUb5cNR5WWRpIRcJp0b6rEujEh8ZDSk";
        $this->telegram_uri_path = "https://api.telegram.org/bot".$this->telegram_token;
	}

    public function index()
    {
        $update = json_decode(file_get_contents("php://input"), true);
        if(!empty($update)) {
            $entities = $update["message"]["entities"];
            if(is_array($entities)) {
                $chatId = $update["message"]["chat"]["id"];
                $username = $update["message"]["chat"]["username"];
                for ($i=0; $i < count($entities); $i++) { 
                    if(empty($entities[$i + 1])) {
                        $text = substr($update["message"]["text"], $entities[$i]["offset"]);
                    }else{
                        $text = substr($update["message"]["text"], $entities[$i]["offset"], $entities[$i + 1]["offset"] - $entities[$i]["offset"]);
                    }

                    $command = substr($text, 1, $entities[$i]["length"] - 1);
                    $value = substr($text, $entities[$i]["length"] + 1);

                    if(method_exists($this, $command)){
                        $this->$command($update, $value);
                    }
                }
            }
        }
        // else{
        //     $command = 'start';
        //     $value = 'WmN4QlFiVzlGZjF6bFp6QVZSTVJJUT09';
        //     $update = array(
        //                 "message" => array(
        //                     "chat" => array(
        //                         "id" => "1015713785",
        //                         "username" => "handika",
        //                         "first_name" => "Handika"
        //                     )
        //                 )
        //             );
        //     $this->$command($update, $value);
        // }

        echo "<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p><hr/><address>".((!empty($_SERVER["SERVER_SIGNATURE"]))? $_SERVER["SERVER_SIGNATURE"] : "localhost")."</address></body></html>";
    }

    private function start($body, $value = null)
    {
         $chatId = $body["message"]["chat"]["id"];
         $first_name = (!empty($body["message"]["chat"]["first_name"])) ? $body["message"]["chat"]["first_name"] : NULL;
         $last_name = (!empty($body["message"]["chat"]["last_name"])) ? $body["message"]["chat"]["last_name"] : NULL;
         $username = (!empty($body["message"]["chat"]["username"])) ? $body["message"]["chat"]["username"] : $first_name . " " . $last_name;
         if($value != null) {
            $id = decrypt_url($value, "telegram_bot_key", false);
            if($id !== false && !empty($id)) {
                $where = array('user_id' => $id, 'pejabat_id' => 3);
				$cek =  $this->db->get_where('pejabat_instansi', $where)->row();
                if(!empty($cek)) {
                    if(empty($cek->telegram_chat_id)) {
                        if($cek->pejabat_id == 3) {
                            $data = [
                                "telegram_chat_id" => $chatId,
                                "updated_at" => date("Y-m-d H:i:s"),
                                "updated_by" => $id
                            ];
                            $query = $this->db->update('pejabat_instansi', $data, ['user_id' => $id , 'pejabat_id' => 3]);
                            $text = "Hai <b>$username</b>,\nBerhasil mengaitkan akun telegram dengan akun aplikasi sikap.\n";
                        }else {
                            $text = "Hai <b>$username</b>,\nSepertinya link yang anda gunakan untuk mengaitkan akun tidak valid. Pastikan anda mengklik tombol dari aplikasi sikap.\n";
                        }
                    } else {
                        $text = "Hai <b>$username</b>,\nMohon maaf akun yang anda kaitkan dengan link ini sudah terkait.\n";
                    }
                }else{
                        $text = "Hai <b>$username</b>,\nSepertinya link yang anda gunakan untuk mengaitkan akun tidak valid. Pastikan anda mengklik tombol dari aplikasi sikap.\n";
                }
            }else{
                $text = "Hai <b>$username</b>,\nSepertinya link yang anda gunakan untuk mengaitkan akun tidak valid. Pastikan anda mengklik tombol dari aplikasi sikap.\n";
            }

            telegram_send($chatId, $text);
        }
    }
}

/* End of file Notif.php */