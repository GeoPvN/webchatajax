<?php

require 'mysqli.class.php';
$mysqli = new dbClass();

if($_REQUEST[act] == 'check_ip'){
    $next = 0;
    $ip = get_client_ip();
    $myquery = $mysqli->setQuery("  SELECT    `id`
                                    FROM      `blocked`
                                    WHERE     `ip` = '$ip'");
    $json = $mysqli->getResultArray($myquery);
    $json = $mysqli->getNumRow();
    if($json > 0){
        $next = 1;
    }
    echo $next;
}elseif($_REQUEST[act] == 'like'){
    $mysqli->setQuery(" UPDATE  `chat` SET
                                `vote`='$_REQUEST[value]'
                        WHERE   `id`='$_REQUEST[chat_id]'");
    
    $mysqli->execQuery();
}elseif($_REQUEST[act] == 'comment'){
    $mysqli->setQuery(" UPDATE  `chat` SET
                                `vote_comment`='$_REQUEST[value]'
                        WHERE   `id`='$_REQUEST[chat_id]'");
    
    $mysqli->execQuery();
}elseif($_REQUEST[act] == 'chat_check'){
    $mysqli->setQuery(" SELECT  chat_details.id,`message_operator`,
                                IF(ISNULL(chat_nikname.`name`),'ოპერატორი',`chat_nikname`.`name`) AS `op_name`
                        FROM    `chat_details`
                        LEFT JOIN `chat_nikname` ON chat_details.operator_user_id = chat_nikname.crystal_users_id
                        WHERE   `chat_details`.`chat_id` = '$_REQUEST[chat_id]' AND chat_details.operator_user_id > 0 ORDER BY chat_details.id DESC LIMIT 1");
 
    echo $mysqli->getJson();
}elseif($_REQUEST[act] == 'upload_file'){
        $element	= 'choose_file';
		$file_name	= $_REQUEST['file_name'];
		$type		= $_REQUEST['type'];
		$path		= $_REQUEST['path'];
		$path		= $path . $file_name . '.' . $type;
		$upName     = $file_name . '.' . $type;
		if($type == 'pdf' || $type == 'docx' || $type == 'xlsx'){
		    $gg = 1;
		}else{
		    $gg = 2;
		}
		
		if (! empty ( $_FILES [$element] ['error'] )) {
			switch ($_FILES [$element] ['error']) {
				case '1' :
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2' :
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3' :
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4' :
					$error = 'No file was uploaded.';
					break;
				case '6' :
					$error = 'Missing a temporary folder';
					break;
				case '7' :
					$error = 'Failed to write file to disk';
					break;
				case '8' :
					$error = 'File upload stopped by extension';
					break;
				case '999' :
				default :
					$error = 'No error code avaiable';
			}
		} elseif (empty ( $_FILES [$element] ['tmp_name'] ) || $_FILES [$element] ['tmp_name'] == 'none') {
			$error = 'No file was uploaded..';
		} else {
			if (file_exists($path)) {
				unlink($path);
			}
			$mysqli->setQuery(" INSERT INTO `chat_file`
			    (`chat_id`, `name`, `rand_name`)
			    VALUES
			    ('$_REQUEST[chat_id]', NULL, '$upName');");
			
			$mysqli->execQuery();
			
			move_uploaded_file ( $_FILES [$element] ['tmp_name'], $path);
		
			// for security reason, we force to remove all uploaded file
			
			@unlink ( $_FILES [$element] );
			echo $gg;
		}
		
}elseif($_REQUEST[act] == 'close_chat'){
    $mysqli->setQuery(" UPDATE  `chat` SET
                                `status`='3'
                        WHERE   `id`='$_REQUEST[chat_id]' AND `status` != 4");
    
    $mysqli->execQuery();
    
}elseif($_REQUEST[act] == 'send_mail'){    
    date_default_timezone_set('Etc/UTC');
    header('Content-Type: text/html; charset=utf-8');
    
    require_once('PHPMailerAutoload.php');
    $address = $_REQUEST['mail'];
    $chat_id = $_REQUEST['chat_original_id'];
    $user_mail = "info@crystalbet.com";
    
    $myquery = $mysqli->setQuery("  SELECT IF(operator_user_id = 0,chat_details.message_client,message_operator),
                                    IF(operator_user_id = 0,chat.`name`,IF(ISNULL(chat_nikname.`name`),'ოპერატორი',chat_nikname.`name`)),
                                    message_datetime
                                    FROM `chat`
                                    JOIN chat_details ON chat.id = chat_details.chat_id AND message_operator != 'daixura'
                                    LEFT JOIN chat_nikname ON chat_details.operator_user_id = chat_nikname.crystal_users_id
                                    WHERE chat.id = $chat_id;");
    $json = $mysqli->getResultArray($type=MYSQLI_NUM);

    $body = '<table>';
    foreach ($json[result] AS $ky){
        $body .= '<tr>
            <td style="width: 150px;">'.$ky[1].'</td><td style="word-break: break-word;width: 385px;">'.$ky[0].'</td><td>'.$ky[2].'</td>
            </tr>';
    }
    $body .= '</table>';
    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    // Set PHPMailer to use the sendmail transport
    $mail->isSendmail();
    //Set who the message is to be sent from
    $mail->setFrom($user_mail, 'crystalbet.com');
    //Set an alternative reply-to address
    $mail->addReplyTo($user_mail, 'crystalbet.com');
    
    $mail->addAddress($address);
    
    $mail->AddCC($address);
    
    $mail->AddBCC($address);
    
    $mail->Subject = 'Crystalbet Chat';
    
    $mail->msgHTML($body);
    
    //send the message, check for errors
    if (!$mail->send()) {
        //echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        //echo "Message sent!";
    }
    
}else if($_REQUEST[act] == 'off_on'){
    $myquery = $mysqli->setQuery("SELECT COUNT(id) AS `on_off` FROM `crystal_users` WHERE `online` = 1");

    echo $mysqli->getJson();
}else{
    $myquery = $mysqli->setQuery("SELECT    `ip_count`,
                                            `welcome_on_off`,
                                            `welcome_text`,
                                            `input_on_off`,
                                            `input_name`,
                                            `input_mail`,
                                            `input_phone`,
                                            `sound_on_off`,
                                            `color`,
                                            `text_color`,
                                            `send_file`,
                                            `send_mail`,
                                            `send_sound`,
                                            `send_close`,
                                            `taimer`
                                  FROM      `chat_setting`;");
    $json = $mysqli->getResultArray($myquery);
    
    echo $mysqli->getJson();
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>