<?php 
	require 'database.php';
	require 'sendMail.php';

	$cronRuningTime = '09:00'; // configure time for cron format(HH:MM)
	$date = new DateTime();
	$date->setTimezone(new DateTimeZone('GMT+3'));	
	$dateForRecord = $date->format('d/m/Y');
	$timeForCron = $date->format('H:i');
	if($timeForCron == $cronRuningTime){
		$conn = dbConnection();
		$data = getReservationData($dateForRecord);
		if($data && count($data) > 0){
			foreach ($data as $user) {
				$statment = "<body>
					<p style='direction:rtl'>היי ".$user['name'].", </p>
					<p style='direction:rtl'>נוף להר שמחים לארח אותך ומצפים לבואך היום. </p>   
					<p style='direction:rtl'>בעקבות נגיף הקורונה ועל פי הנחיות משרד הבריאות הנך נדרש לחתום על טופס הצהרת הבריאות לפני כניסתך. </p>   					
                                        <p style='direction:rtl'>לשמירה על בריאותך וכלל האורחים, אנו מאפשרי לעשות זאת בקלות ובאופן דיגיטלי. אנא מלא/י את הטופס כעת בלינק הבא:</p>   	
					<p style='direction:rtl'><a href='http://mvv.co.il/reservation/covid19'>http://mvv.co.il/reservation/covid19</a></p>
					<p style='direction:rtl'>במידה ושינית או ביטלת את ההזמנה ואינך מגיע היום - אנא התעלם ממייל זה.</p>
				</body>";
				$mail = sendMailWithContent($user['name'], $user['email'], "נוף להר - הצהרת בריאות", $statment);
				if($mail){
					deleteReservation($user['id']);
				}
			}
		}
	}

?>
