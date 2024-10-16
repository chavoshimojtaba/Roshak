<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception; 

class Message extends Controller
{ 
	
	public $params = []; 
	public $AddressList = []; 
	public $subject = ''; 
	public $text = ''; 
	public $template = 0; 
	
	public function __construct($template,$emails)
	{
		parent::__construct(); 
	
		$this->AddressList  		= $emails;
		$this->template	    		= $template; 
	}

	function send($data)
	{
		$this->load->model('model_notifications');
		$res 	       = $this->model_notifications->get_template($this->template);
		$type 	 	   = $res['type'];
		$exp 	 	   = decode_html_tag($res['exp'],true);
		$this->title   = decode_html_tag($res['title'],true);
		$this->text    = $this->html->assign_attr($exp,$data); 
		if($type == 'email'){
			$this->email();
		}else if($type == 'sms'){
			$this->params    = $data;
			$this->sms($res['sms_type'],$res['TemplateId']); 
		}
		return true;
	} 

	function email()
	{ 
		try { 
			$mail 			  = new PHPMailer();
			$mail->SMTPKeepAlive = true; 
			$mail->Mailer 	  = "smtp"; 
			$mail->SMTPAuth   = TRUE;
			$mail->CharSet    = "UTF-8"; 
			$mail->Port       = 587;
			$mail->Host       = "smtp.gmail.com";           //Enable SMTP authentication
			$mail->Username   = 'chavoshi.adv2@gmail.com';  //SMTP username
			$mail->Password   = 'vihmjkokdhyovpxa';         //TCP port to connect to; use 587 if you have set `SMTPSecure = 
			$mail->isHTML(true);
			$mail->IsSMTP();
			foreach ($this->AddressList as $value) { 
				$mail->addAddress($value);
			}
			$mail->Subject 	  = $this->title;
			$mail->Body    	  = $this->EmailHtml();
			$mail->send();
		} catch (Exception $e) { 
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}

	function sms($sms_type,$TemplateId)
	{ 
		$res = false;
		require_once(DIR_LIBRARY . "SMS.php"); 
		try { 
			$SmsIR_SendMessage = new SmsIR_SendMessage(); 
			switch ($sms_type) {
				case 'usual': 
					$Mgs        = []; 
					for ($i=0; $i < count($this->AddressList); $i++) { 
						$Mgs[]  = $this->text;
					}    
					$res = $SmsIR_SendMessage->sendMessage($this->AddressList, $Mgs );
					break;
				case 'fast': 
					$arr = [];
					foreach ($this->params as $key => $value) {
						$arr[] = [
							"Parameter" => $key,
							"ParameterValue" => $value
						];
					}
					$data = array(
						"ParameterArray" => $arr,
						"Mobile" => $this->AddressList[0],
						"TemplateId" => $TemplateId
					); 
					$res = $SmsIR_SendMessage->ultraFastSend($data); 
					break;
				case 'verify': 
					$Code = $this->params['code'];  
					$res = $SmsIR_SendMessage->VerificationCode($Code, $this->AddressList[0]); 
					break;  
			} 
			 
        	return $res;
        } catch (Exeption $e) { 
            return $e->getMessage();
        }
	}
	
	function EmailHtml()
	{
		return '<html lang="fa" dir="rtl"><head><meta charset="UTF-8"><title>'.LANG_WEBSITE_NAME.'</title></head><body style="margin: 0px; background: #f8f8f8"><div width="100%"
		style=" background: #f8f8f8; padding: 0px 0px; font-family: arial; line-height: 28px; height: 100%; width: 100%; color: #514d6a; "><div style=" max-width: 700px; padding: 15px 0; margin: 0px auto; font-size: 14px; "><div style="padding: 20px; background: #fff"><table border="0" cellpadding="0" cellspacing="0"
		style="width: 100%;direction:rtl;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important;"><tbody><tr><td style="vertical-align: top;direction:rtl" align="center"><a href="#"
		target="_blank"><img src="https://Tarhpich.com/custom/img/LOGO.jpg" alt="xtreme admin"
		height="50px" style="border: none" /></a></td></tr><tr><td style="border-bottom: 1px solid #f6f6f6"><h1
		style=" font-size: 14px; font-family: arial; margin: 0px; font-weight: bold;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important; ">کاربر عزیز: </h1><p
		style="margin-top: 0px; color: #bbbbbb;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important;">'.$this->subject.' </p></td></tr><tr><td
		style="padding: 10px 0 10px 0;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important;">'.$this->text.'</td></tr><tr><td
		style=" border-top: 1px solid #f6f6f6; padding-top: 20px; color: #777;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important; ">توجه : این ایمیل به صورت خودکار فرستاده شده است ، بنابراین به آن جواب ندهید و از طریق
		ناحیه کاربری نتیجه را پیگیری نمایید. </td></tr></tbody></table></div><div style=" text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px; "><p>Powered by Tarhpich Co <br /><a href="javascript: void(0);"
		style="color: #b2b2b5; text-decoration: underline;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important;">'.LANG_FOOTER_COPYRIGHT.'</a></p></div></div></div></body></html>';
	}

}
