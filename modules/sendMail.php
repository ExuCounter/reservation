<?php
	// For error reporting on production mode
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require '../libs/PHPMailer/src/Exception.php';
	require '../libs/PHPMailer/src/PHPMailer.php';
	require '../libs/PHPMailer/src/SMTP.php';

	function initPhpMailer(){
		$SMTP_USERNAME = "info@mvv.co.il";
		$SMTP_PASSWORD = "uqsasydvqtihqrcu";
		$SMTP_HOST = "smtp.gmail.com";
		$mail = new PHPMailer(true);
		$mail->SMTPDebug  = 0;  
		$mail->SMTPAuth   = TRUE;
		$mail->SMTPSecure = "tls";
		$mail->Port       = 587;
		$mail->Host       = $SMTP_HOST;
		$mail->Mailer   = "smtp";
		$mail->CharSet = 'UTF-8';
		$mail->Username   = $SMTP_USERNAME;
		$mail->Password   = $SMTP_PASSWORD;
		$mail->IsHTML(true);
		$mail->SetFrom("info@mvv.co.il", "נוף להר - יחידות נופש");
		return $mail;
	}

	function sendMailWithAttachment($name, $email, $content, $subject, $fileName){
		$mail = initPhpMailer();
		try{
			$mail->AddAddress($email, $name);
			$mail->AddAttachment("../pdf/sample.pdf", $fileName);
			$mail->Subject = $subject;
			$content = $content;
			$mail->MsgHTML($content); 
			$mail = $mail->Send();
			return true;
		}catch (Exception $e) {
			echo $e->getMessage();
			return $e->getMessage();
		}
	}

	function sendMailWithContent($name, $email, $subject, $content){
		$mail = initPhpMailer();
		try{
			$mail->AddAddress($email, $name);
			$mail->Subject = $subject;
			$content = $content;
			$mail->MsgHTML($content); 
			$mail = $mail->Send();
			return true;
		}catch (Exception $e) {
			return false;
		}	
	}
?>