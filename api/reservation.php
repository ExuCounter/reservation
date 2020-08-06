<?php

	// For error reporting on production mode
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once './tcpdf_include.php';
	require '../modules/sendMail.php';
	require '../modules/database.php';
	
	if($_SERVER["REQUEST_METHOD"] != "POST"){
		Header("HTTP/1.1 400 Bad Request");
		echo json_encode(['message'=>"Request not allowed"]);
		return;		
	}

	$firstName = $_REQUEST['firstName'];
	$lastName = $_REQUEST['lastName'];
	$mobileNumber = $_REQUEST['mobileNumber'];
	$mailAddress = $_REQUEST['mailAddress'];
	$roomTypeOne = $_REQUEST['roomTypeOne'];
	$roomTypeTwo = $_REQUEST['roomTypeTwo'];
	$roomType = '';
	if($roomTypeOne == 'true')
		$roomType = "סוויטת חדר שינה אחד";
	else
		$roomType = "סוויטת 2 חדרי שינה";
	$policy = $_REQUEST['policy'];
	$adult = $_REQUEST['adult'];
	$children = $_REQUEST['children'];
	$arrivalDate = $_REQUEST['arrivalDate'];
	$depatureDate = $_REQUEST['depatureDate'];
	$orderAmount = $_REQUEST['orderAmount'];
	$cardHolderName = $_REQUEST['cardHolderName'];
	$cardId = $_REQUEST['cardId'];
	$cardNumber = $_REQUEST['cardNumber'];
	$expiryYear = $_REQUEST['expiryYear'];
	$expiryMonth = $_REQUEST['expiryMonth'] > 9 ? $_REQUEST['expiryMonth'] : '0'.$_REQUEST['expiryMonth'];
	$cvv = $_REQUEST['cvv'];
	$signTxt = $_REQUEST['signTxt'];
	$signatureImg = $_REQUEST['signatureImg'];
	$message = $_REQUEST['message'];
	$callDetails = $_REQUEST['callDetails'];
	$signDate = $_REQUEST['signDate'];
	$address = $_REQUEST['address'];
	$city = $_REQUEST['city'];

	$img_base64_encoded = $signatureImg;
	$imageContent = file_get_contents($img_base64_encoded);
	$path = tempnam('../pdf', 'prefix');
	file_put_contents ($path, $imageContent);
	// Generate PDF Process
	$png = 'https://mvv.co.il/reservation/assets/img/pdf-logo.jpg';
	$html = <<<EOF
			<div style="text-align:center;">
				<img src="$png" width="150">
			</div>
			<div style="color:#004884;text-align:center;font-size:15px;"><b>טופס הזמנה</b></div>
			<table style="padding:0px 0px;">
				<tr>
					<td align="right" style="border-bottom:1px solid #ced4da;">
						<span style="color:#004884;font-size:11px"><b>פרטים אישיים</b></span>
					</td>
				</tr>
			</table>
			<table width="100%" style="padding:7px 0px 0px 0px;">
				<tr style="padding:0;margin:0">
					<td align="right"><span class="field"><b>$mobileNumber</b>מספר טלפון נייד: </span></td>
					<td align="right"><span class="field"><b>$lastName</b>שם משפחה: </span></td>
					<td align="right"><span class="field"><b>$firstName</b>שם פרטי: </span></td>
				</tr>
				<tr style="padding:0;margin:0">
					<td align="right"><span class="field"><b>$mailAddress</b>כתובת מייל: </span></td>
					<td align="right"><span><b>$address</b>כתובת מגורים: </span></td>
					<td align="right"><span><b>$city</b>עיר מגורים: </span></td>
				</tr>
				<tr><td></td></tr>
			</table>
			<table style="padding:0px 0px;">
				<tr>
					<td align="right" style="border-bottom:1px solid #ced4da;">
						<span style="color:#004884;font-size:11px"><b>פרטי הזמנה</b></span>
					</td>
				</tr>
			</table>
			<table width="100%" style="padding:7px 0px 0px 0px;">
				<tr style="padding:0;margin:0">
					<td align="right"><span class="field"><b>$children</b>מספר ילדים: </span></td>
					<td align="right"><span class="field"><b>$adult</b>מספר מבוגרים: </span></td>
					<td align="right"><span class="field"><b>$roomType</b>סוג החדר: </span></td>
				</tr>
				<tr style="padding:0;margin:0">
					<td align="right"><span class="field"><b>$callDetails</b>פרטי התקשרות של ספקים חיצונים: </span></td>
					<td align="right"><span class="field"><b>$depatureDate</b>תאריך עזיבה: </span></td>
					<td align="right"><span class="field"><b>$arrivalDate</b>תאריך הגעה: </span></td>
				</tr>
				<tr style="padding:0;margin:0">
					<td colspan="2" width="88.5%" align="right"><b>$message</b></td>
					<td width="11.5%" align="right"><span>בקשות מיוחדות: </span></td>
				</tr>
				<tr><td></td></tr>
			</table>
			<table style="padding:0px 0px;">
				<tr>
					<td align="right" style="border-bottom:1px solid #ced4da;">
						<span style="color:#004884;font-size:11px"><b>אמצעי תשלום</b></span>
					</td>
				</tr>
			</table>
			<table width="100%" style="padding:7px 0px 0px 0px;">
				<tr style="padding:0;margin:0">
					<td align="right"><span class="field"><b>$cardId</b>תעודת זהות: </span></td>
					<td align="right"><span class="field"><b>$cardHolderName</b>שם בעל הכרטיס: </span></td>
					<td align="right"><span class="field"><b>$orderAmount</b>סכום ההזמנה: </span></td>
				</tr>
				<tr style="padding:0;margin:0">
					<td align="right"><span class="field"><b>$cvv</b>קוד אבטחה (CVV): </span></td>
					<td align="right"><span class="field"><b>$expiryMonth/$expiryYear</b>תוקף הכרטיס: </span></td>
					<td align="right"><span class="field"><b>$cardNumber</b>מספר כרטיס: </span></td>
				</tr>
				<tr><td></td></tr>
			</table>
			<table style="padding:0px 0px;">
				<tr>
					<td align="right" style="border-bottom:1px solid #ced4da;">
						<span style="color:#004884;font-size:11px"><b>המדיניות שלנו</b></span>
					</td>
				</tr>
			</table>
			<table width="100%" style="padding:0px 0px 0px 0px;">
				<tr><td></td></tr>
				<tr>
					<td style="padding:0px;margin:0px" align="right"><b>ביצוע הזמנה</b></td>
				</tr>
				<tr style="padding:0;margin:0">
					<td align="right">תהליך ביצוע ההזמנה הנו פשוט וקל, הנך נדרש לתאם עמנו בטלפון או בווטסאפ את תאריכי ההזמנה המבוקשים, עם וידוא תאריכי ההזמנה והסכמתך להצעה המחיר מאתנו תתבקש/י למלא ולחתום על טופס הזמנה, בסיום מילוי הטופס ישלח אלייך עותק לכתובת המייל שברשותך. מילוי טופס ההזמנה ללא תאום מראש ואישור סטטוס ההזמנה על ידנו אינו מהווה אישור הזמנה.</td>
				</tr>
			</table>
			<table width="100%" style="padding:0px 0px 0px 0px;">
				<tr><td></td></tr>
				<tr>
					<td align="right"><b>ביטול הזמנה</b></td>
				</tr>
				<tr>
					<td align="right">עפ החוק להגנת הצרכן, הנך רשאי/ת לבטל את ההזמנה בכתב בלבד תוך 14 ימי עסקים מיום ביצוע ההזמנה בעלות דמי ביטול של 5% או 100 שח – הנמוך מבניהם, ובתנאי שביטול ההזמנה אינו חל בתוך 7 הימים הקודמים למועד הגעתך.</td>
				</tr>
				<tr>
					<td align="right">אנו משקיעים את מירב המאמצים לשמירת מקומך והבטחת חווית השהיה שלך, ביטול ההזמנה לאחר 14 ימי עסקים מיום ביצוע ההזמנה או בתוך 7 ימי עסקים הקודמים למועד הגעתך, יחויבו דמי ביטול בעלות 35% מסך ההזמנה. במקרים חריגים של ביטול מצדנו, אנו נעשה את מירב המאמצים להציע חלופות לשביעות רצונך. בכל מקרה של ביטול הזמנה כתוצאה מכוח עליון, מלחמה, או מגפה אנו נפעל עפ הנחיית הרשויות ודמי הביטול לא יחויבו כלל.</td>
				</tr>
			</table>
			<table width="100%" style="padding:0px 0px 0px 0px;">
				<tr><td></td></tr>
				<tr>
					<td align="right"><b>זמני הגעה ויציאה</b></td>
				</tr>
				<tr>
					<td align="right">כניסה ליחידות האירוח תתאפשר ביום האירוח הראשון החל מהשעה 15:00, היציאה הינה ביום האירוח האחרון עד השעה 12:00. עזיבה במוצאי שבת הינה בתאום מראש ובעלות של 200 שח.</td>
				</tr>
			</table>
			<table width="100%" style="padding:0px 0px;">
				<tr><td colspan="2"></td></tr>
				<tr>
					<td colspan="2" align="right"><b>כללי התנהגות</b></td>
				</tr>
				<tr>
					<td align="right" width="98.5%">חל איסור מוחלט על עישון סיגריות ו/או נרגילות בתוך יחידות הנופש.</td>
					<td align="right" width="1.5%"><span style="font-size:12px;line-height:1px"> -</span></td>
				</tr>
				<tr>
					<td align="right" width="98.5%">לנוחיותכם ניתן להשתמש במנגל הקיים במתחם, אין להבעיר אש במהלך השבת.</td>
					<td align="right" width="1.5%"><span style="font-size:12px;line-height:1px"> -</span></td>
				</tr>
				<tr>
					<td align="right" width="98.5%">יחידות הנופש מוגדרת כיחידות שקטות ובאזור מגורים, אין לקיים מסיבות, הכנסת ציוד הגברה או השמעת מוזיקה רועשת. האורחים מתבקשים לשמור על השקט לאחר השעה 23:00.</td>
					<td align="right" width="1.5%"><span style="font-size:12px;line-height:1px"> -</span></td>
				</tr>
				<tr>
					<td align="right" width="98.5%">אין לצרף אנשים נוספים לחדר או למתחם. הכניסה לבעלי חיים אינה מותרת.</td>
					<td align="right" width="1.5%"><span style="font-size:12px;line-height:1px"> -</span></td>
				</tr>
				<tr>
					<td align="right" width="98.5%">אין לקחת מגבות או חפצים אחרים מחוץ למתחם. יש לשמור על תקינות הציוד במתחם ולהודיע על כל נזק שנגרם שלא במתכוון, נזקים שיגרמו מרשלנות, וונדליזם או במתכוון יחויבו בתשלום. </td>
					<td align="right" width="1.5%"><span style="font-size:12px;line-height:1px"> -</span></td>
				</tr>
				<tr>
					<td align="right" width="98.5%">השימוש בג"קוזי ושמירה על כללי הזהירות הינם באחריות האורחים בלבד. </td>
					<td align="right" width="1.5%"><span style="font-size:12px;line-height:1px"> -</span></td>
				</tr>
			</table>
			<table width="100%" style="padding:0px 0px 0px 0px;">
				<tr><td></td></tr>
				<tr>
					<td align="right"><b>נגיף הקורונה</b></td>
				</tr>
				<tr>
					<td align="right">ע"פ הנחיות משרד הבריאות הנך נדרש לחתום על טופס הצהרת בריאות עם הגעתך למתחם. אנו נשלח אלייך מייל נפרד ביום הגעתך אותו תוכל למלא ולחתום באופן דיגיטלי מהטלפון או המחשב.</td>
				</tr>
			</table>
			<table style="padding:0px 0px;"><tr><td align="right" style="border-bottom:1px solid #ced4da;"></td></tr></table>
			<table width="100%" style="padding:7px 0px 0px 0px;">
				<tr>
					<td colspan="4" align="right">אני מאשר/ת שקראתי את מדיניות ההזמנה ואני מסכים/ה לה<span style="font-size:10px;"> &#9745;</span></td>
				</tr>
				<tr>
					<td></td>
				</tr>
				<tr>
					<td align="left">
						<img src="$path" width="65">
					</td>
					<td align="left">חתימה:</td>
					<td align="right"><span class="field"><b>$signDate</b>תאריך: </span></td>
					<td align="right"><span class="field"><b>$signTxt</b>שם: </span></td>
				</tr>
			</table>
		EOF;
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetFont('dejavusans', '', 8.5);
	$pdf->setRTL(false);
	$pdf->SetPrintHeader(false);
	$pdf->SetPrintFooter(false);
	$pdf->AddPage();
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output(dirname(__FILE__).'/../pdf/sample.pdf', 'F');
	unlink($path);

	// Send Mail with atttachment to user
	$sendMailToCustomer = sendMailWithAttachment($firstName, $mailAddress, 'תודה שבחרת נוף להר, בקשת ההזמנה שלך התקבלה אצלנו. עותק ההזמנה מצורף למייל זה', 'נוף להר - עותק הזמנה', 'נוף להר - הזמנה.pdf');

	// Send Mail with atttachment to Owner
	$sendMailToOwner = sendMailWithAttachment("Mountain View", "info@mvv.co.il", 'יש לך הזמנה חתומה חדשה בקובץ המצורף', 'הזמנה חדשה', str_replace('/', '-', $arrivalDate).'.pdf');

	// Add record to DB(reservation table)
	$storeData = createReservation($firstName, $mailAddress, $arrivalDate);

	if($storeData && $sendMailToOwner && $sendMailToCustomer){
		header('Content-Type: application/json');
		echo json_encode(['message'=>"Thanks, the form was successfully sent."]);
	}else{
		header("HTTP/1.1 500");
		echo json_encode(['error'=>"Something went wrong please try again.", "sendcustomeer"=>$sendMailToCustomer, 'sendMailToOwner'=>$sendMailToOwner, 'storeData'=>$storeData]);
	}
?>
