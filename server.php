<?php

set_time_limit(0);
date_default_timezone_set('Asia/Tbilisi');

include 'mysqli.class.php';
$mysqli = new dbClass();

    global $mysqli;
    $esc_message = addslashes($_REQUEST[message]);
    $ms = $_REQUEST[message];
    $esc_name = addslashes($_REQUEST[chat_name]);
    $esc_mail = addslashes($_REQUEST[chat_mail]);
    $esc_phone = addslashes($_REQUEST[chat_phone]);
    $os = $_REQUEST[os];
    $br = $_REQUEST[browser];
    $user = $_REQUEST[user_name];
    $user_id = $_REQUEST[user_id];
    $chat_name = $_REQUEST[chat_name];
    $idd = $_REQUEST[id];
//     $esc_message = $mysqli->real_escape_string($esc_message);
//     $esc_name = $mysqli->real_escape_string($esc_name);
//     $esc_mail = $mysqli->real_escape_string($esc_mail);
//     $esc_phone = $mysqli->real_escape_string($esc_phone);
    $chat_id = $_REQUEST[chat_id];
    $ip = get_client_ip();
    

    if($chat_id == 0){
        if(strlen($chat_name) == 0){
            $gest_name = "სტუმარი $clientID";
        }else{
            $gest_name = $esc_name;
        }
    $mysqli->setQuery(" INSERT INTO `chat`
                        (`join_date`, `answer_date`, `answer_user_id`, `name`, `email`, `phone`, `ip`, `country`, `device`, `browser`, `status`, `socet_id`)
                        VALUES
                        (NOW(), '', '', '$gest_name', '$esc_mail', '$esc_phone', '$ip', '', '$os', '$br', '1', '$clientID');");
    $mysqli->execQuery();
    $chat_id = $mysqli->getLastId();
    $json = array('chat_id_paste'=>$chat_id);
    echo json_encode($json,JSON_NUMERIC_CHECK);
    }else{
        $json = array('chat_id_paste'=>'');
        echo json_encode($json,JSON_NUMERIC_CHECK);
    }
    
    if($idd == 0){
        $mysqli->setQuery(" UPDATE  `chat` SET
                                    `answer_date`=NOW(),
                                    `answer_user_id`='$user_id',
                                    `status`='2'
                            WHERE   `id`='$chat_id' AND status = 1;");
        $mysqli->execQuery();
    }
    
    if($idd == 1){
        if($esc_message != ''){
        $mysqli->setQuery(" UPDATE  `chat` SET
                                    `last_person`='0'
                            WHERE   `id`='$chat_id';");
        $mysqli->execQuery();
        $mysqli->setQuery(" INSERT INTO `chat_details`
                            (`chat_id`,`message_client`, `message_operator`, `operator_user_id`, `message_datetime`)
                            VALUES
                            ('$chat_id', '$esc_message', '', '', NOW());");
        
        $mysqli->execQuery();
        }
    }else{
        if($esc_message != ''){
        $mysqli->setQuery(" UPDATE  `chat` SET
                                    `last_person`='1'
                            WHERE   `id`='$chat_id';");
        $mysqli->execQuery();
        $mysqli->setQuery(" INSERT INTO `chat_details`
                            (`chat_id`,`message_client`, `message_operator`, `operator_user_id`, `message_datetime`)
                            VALUES
                            ('$chat_id', '', '$esc_message', '$user_id', NOW());");
        
        $mysqli->execQuery();
        }
    }

    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
?>
