<?php
	// For error reporting on production mode
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once './tcpdf_include.php';
	require '../modules/sendMail.php';
	if($_SERVER["REQUEST_METHOD"] != "POST"){
		Header("HTTP/1.1 400 Bad Request");
		echo json_encode(['message'=>"Request not allowed"]);
		return;		
	}
	$Q1 = $_REQUEST['q1'] == 'Yes' ? 'כן' : 'לא';
	$Q2 = $_REQUEST['q2'] == 'Yes' ? 'כן' : 'לא';
	$Q3 = $_REQUEST['q3'] == 'Yes' ? 'כן' : 'לא';
	$Q4 = $_REQUEST['q4'] == 'Yes' ? 'כן' : 'לא';
	$Q5 = $_REQUEST['q5'] == 'Yes' ? 'כן' : 'לא';
	$date = new DateTime();
	$fileName = $date->format('d-m-Y');
	$firstName = $_REQUEST['firstName'];
	$lastName = $_REQUEST['lastName'];
	$idNumber = $_REQUEST['idNumber'];
	$fullAddress = $_REQUEST['fullAddress'];
	$datepick = $_REQUEST['datepick'];
	$signatureImg = $_REQUEST['signatureImg'];

	$img_base64_encoded = $signatureImg;
	$imageContent = file_get_contents($img_base64_encoded);
	$path = tempnam('../pdf', 'prefix');
	file_put_contents ($path, $imageContent);

	$png = 'https://mvv.co.il/reservation/assets/img/pdf-logo.jpg';
	$html = <<<EOF
		<div style="text-align:center;margin-top:10px;">
			<img src="$png" width="150">
		</div>
		<div style="color:#004884;text-align:center;margin-bottom:0;font-size:15px;"><b>הצהרת בריאות</b></div>
		<table width="100%" style="padding:5px 0px;">
			<tr><td align="right"><b>אורח/ת יקר/ה,</b></td></tr>
			<tr><td align="right">לאור התפשטות נגיף הקורונה ובהמשך להנחיית משרד הבריאות ולמדיניותה החדשה אנו פועלים למימוש ההנחיות ולשמירת בריאות כלל אורחינו. לפיכך הנך (בעל ההזמנה) נדרש למלא ולחתום על הצהרה זו לפני כניסתך.</td></tr>
			<tr><td align="right">הצהרה זו הינה עבורך ועבור כלל האורחים שבהזמנה.</td></tr>
		</table>
		<div style="border-bottom:1px solid #ced4da;">
		</div>
		<table width="100%" style="padding:5px 0px;">
			<tr><td colspan="2" align="right">אנא סמן/י "כן"/"לא" במקום המיועד לכך:</td></tr>
			<tr>
				<td width="40%" align="right"><b>$Q1</b></td>
				<td width="60%" align="right">א. האם הנך או מי מהאורחים חזר מחו"ל במהלך 14 הימים האחרונים?</td>
			</tr>
			<tr>
				<td width="40%" align="right"><b>$Q2</b></td>
				<td width="60%" align="right">ב. האם מישהו מבני ביתך נמצא בבידוד ביתי?</td>
			</tr>
			<tr>
				<td width="40%" align="right"><b>$Q3</b></td>
				<td width="60%" align="right">ג. האם הנך או מי מהאורחים נחשף למישהו החשוד כחולה או נשא קורונה?</td>
			</tr>
			<tr>
				<td width="40%" align="right"><b>$Q4</b></td>
				<td width="60%" align="right">ד. האם ככל הידוע לך, עליך או על מי מהאורחים לשעות בבידוד?</td>
			</tr>
			<tr>
				<td width="40%" align="right"><b>$Q5</b></td>
				<td width="60%" align="right">ה. האם הנך או מי מהאורחים סובל בימים אלו מחום / שיעול / קשיי נשימה?</td>
			</tr>
		</table>
		<table width="100%" style="padding:5px 0px;">
			<tr>
				<td align="right">אני הח"מ מצהיר/ה כי ערכתי היום בדיקה למדידת חום הגוף שלי ושל כלל האורחים שבהזמנה, בה נמצא כי חום הגוף שלי ושל כלל האורחים אינו עולה על 37.5 מעלות צלזיוס.</td>
			</tr>
			<tr>
				<td align="right">אני הח"מ מצהיר/ה כי אינני משתעל/ת, וכי אין לי קשיים בנשימה וכן לכלל האורחים שבהזמנה זו.</td>
			</tr>
			<tr>
				<td align="right">אני הח"מ מצהיר/ה בזאת כי הפרטים שמסרתי נכונים להיום ומציגים באור מלא את המצב הרפואי שלי ושל כלל הארוחים שבהזמנה.</td>
			</tr>
			<tr>
				<td align="right">ידוע לי כי העלמת מידע והצהרה לא נכונה הינה עבירה על החוק ועלולה להביא לעונשים הכתובים בחוק.</td>
			</tr>
		</table>
		<table width="100%" style="padding:10px 0px 0px 0px;">
			<tr><td></td></tr>
		</table>
		<table style="padding:0px 0px;">
			<tr>
				<td align="right" style="border-bottom:1px solid #ced4da;">
					<span style="color:#004884;font-size:11px"><b>פרטים אישיים</b></span>
				</td>
			</tr>
		</table>
		<table width="100%" style="padding:7px 0px 0px 0px;">
			<tr>
				<td align="right"><span><b>$idNumber</b></span>מספר תעודת זהות :</td>
				<td align="right"><span><b>$lastName</b></span>שם משפחה :</td>
				<td align="right"><span><b>$firstName</b></span>שם פרטי :</td>
			</tr>
			<tr>
				<td colspan="3" align="right"><span><b>$fullAddress</b></span>כתובת מלאה :</td>
			</tr>
		</table>
		<table width="100%" style="padding:15px 0px 0px 0px;">
			<tr>
				<td></td>
				<td align="right">
					<img src="$path" width="80">
				</td>
				<td align="left">חתימה:</td>
				<td align="right"><span class="field"><b>$datepick</b>תאריך: </span></td>
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
	$sendMailToOwner = sendMailWithAttachment("Mountain View", "info@mvv.co.il", 'יש לך הצהרת בריאות חתומה חדשה בקובץ המצורף', 'הצהרת בריאות חדשה', $fileName.'.pdf');
	if($sendMailToOwner){
		header('Content-Type: application/json');
		echo json_encode(['message'=>"Thanks, the form was successfully sent."]);
	}else{
		header("HTTP/1.1 500");
		header('Content-Type: application/json');
		echo json_encode(['error'=>"Something went wrong please try again."]);
	}
?>