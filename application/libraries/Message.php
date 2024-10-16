<?php
class Message  extends Controller
{
    public $params = [];
	public $AddressList = [];
	public $send_time = '';
	public $SecretKey = '';
	public $subject = '';
	public $APIURL = '';
	public $customTemplate = '';
	public $LineNumber = '';
	public $text = '';
	public $APIKey = '';
	public $template = 0;

    protected function getAPIMessageReceiveUrl() {
		return "ReceiveMessage";
	}

    protected function getAPIVerificationCodeUrl() {
		return "verify";
	}

    protected function getAPIMessageSendUrl()
    {
        return "bulk";
    }

    protected function getAPIUltraFastSendUrl()
    {
        return "UltraFastSend";
    }

    protected function getApiTokenUrl()
    {
        return "Token";
    }

    public function __construct($template,$addressList)
    {
        date_default_timezone_set("Asia/Tehran");
        parent::__construct();
		$this->AddressList  = $addressList;
		$this->template	    = $template;
        $this->APIKey       = "CUlsqAD5N6b00EwnqZiAQMXbvDPRwf5AKp6NsjZsPeM3bvWuKuFONcetUMh3VpMC";
        $this->SecretKey    = "!@#@!";
        $this->LineNumber   = '30007732004241';
        $this->APIURL       = 'https://api.sms.ir/v1/send/';
    }

    public function sendMessage($MobileNumbers, $Messages )
    {
		/* $msg = [];
		for ($i=0; $i < count($MobileNumbers); $i++) { 
			$msg[] = $Messages[0];
		} */
		// pr($msg);
		$postData = array(
			'messageText' => $Messages[0],
			'mobiles' => $MobileNumbers,
			'sendDateTime' => $this->send_time,
			'lineNumber' => $this->LineNumber
		);  
		// pr($postData);
		$url = $this->APIURL.$this->getAPIMessageSendUrl();
		$SendMessage = $this->executeCurl($postData, $url);
		$object = json_decode($SendMessage); 
		if (is_object($object)) {
			$result = $object;
		} else {
			$result = false;
		}
        return $result;
    }

    public function VerificationCode($Code, $MobileNumber,$TemplateId)
    {
		$postData =  array(
			'parameters'=>[
				[
					"name"=> "CODE",
					"value"=> $Code
				]
			],
			'mobile' => $MobileNumber,
			'templateId' => $TemplateId,
		);
		$url = $this->APIURL.$this->getAPIVerificationCodeUrl();
		$SendMessage = $this->executeCurl($postData, $url);
		$object = json_decode($SendMessage);
		$result = false;
		if (is_object($object)) {
			$result = $object;
		} else {
			$result = false;
		}
		return $result;
	}

    public function ReceiveMessageResponseByDate($Shamsi_FromDate, $Shamsi_ToDate, $RowsPerPage, $RequestedPageNumber)
	{

        $token = $this->_getToken($this->APIKey, $this->SecretKey);
		if($token != false){

			$url = $this->APIURL.$this->getAPIMessageReceiveUrl()."?Shamsi_FromDate=".$Shamsi_FromDate."&Shamsi_ToDate=".$Shamsi_ToDate."&RowsPerPage=".$RowsPerPage."&RequestedPageNumber=".$RequestedPageNumber;
			$ReceiveMessageResponseByDate = $this->execute([],$url, $token);
			$object = json_decode($ReceiveMessageResponseByDate);
			if(is_object($object)){
				$array = get_object_vars($object);
				if(is_array($array)){
					if($array['IsSuccessful'] == true){
						$result = $array['Messages'];
					} else {
						$result = $array['Message'];
					}
				} else {
					$result = false;
				}
			} else {
				$result = false;
			}

		} else {
			$result = false;
		}
		return $result;
	}

    private function executeCurl($postData, $url)
    {
		$curl = curl_init(); 
		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => json_encode($postData),
		  CURLOPT_HTTPHEADER => array(
			'X-API-KEY: '.$this->APIKey,
			'Accept: */*',
			'Content-Type: application/json'
		  ),
		));

		$result = curl_exec($curl);
		curl_close($curl);


        return $result;
    }

    function send($data)
	{
		$template = [];
		
		if($this->template == 'custom'){
			$this->text    = strip_tags($this->customTemplate);
			$template['TemplateId'] = 1000;
			$type 	 	   = $data['type'];
			// pr($this->text,true);
			$template['sms_type'] = 'usual';
		}else{ 
			$this->load->model('model_notifications');
			$res 	       = $this->model_notifications->get_template($this->template);
			// //pr($this->template,true);
			if($res->count>0){
				$template = $res->result[0];
				$type 	 	   = $template['type'];
				$exp 	 	   = decode_html_tag($template['exp'],true);
				$this->title   = decode_html_tag($template['title'],true);
				$this->text    = $this->html->assign_attr($exp,$data);
				$this->text    = strip_tags($this->text);
			}else{
				return false;
			}
		}

		if($type == 'email'){
			$this->email();
		}else if($type == 'sms'){
			$this->params    = $data;
			$this->text =  strip_tags($this->text);
			return $this->sms($template['sms_type'],$template['TemplateId']);
		}
		return true;
	}

	function email()
	{
		try {
			$mail 			  = new PHPMailer\PHPMailer\PHPMailer();
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
			$mail->Subject 	  = LANG_WEBSITE_NAME;
			$mail->Body    	  = $this->EmailHtml();
			$mail->send(); 
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}

	function sms($sms_type,$TemplateId)
	{
		$res = false;  
		try {
			switch ($sms_type) {
				case 'usual':
					$Mgs        = [$this->text];
					$res = $this->sendMessage($this->AddressList, $Mgs );
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
					$res = $this->ultraFastSend($data);
					break;
				case 'verify':
					$Code = $this->params['CODE'];
					foreach ($this->AddressList as $key => $value) {
						$res = $this->VerificationCode($Code ,$value ,$TemplateId);
					}
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
		style=" background: #f8f8f8; padding: 0px 0px; font-family: arial; line-height: 28px; height: 100%; width: 100%; color: #514d6a; position:relative"><div style=" max-width: 700px; padding: 15px 0; margin: 0px auto; font-size: 14px; "><div style="padding: 20px; background: #fff;position: relative;overflow: hidden;"><table border="0" cellpadding="0" cellspacing="0"
		style="width: 100%;direction:rtl;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important;text-align: center;"><tbody><tr><td style="vertical-align: top;direction:rtl;height: 80px;" align="center"><a href="https://tarhpich.ir" target="_blank" style="position: absolute;left: 0;width: 100%;background-color: #fff;top: -22px;height: 100px;border-radius: 0 0 149px 132px;"><img src="https://tarhpich.ir/file/client/images/logo.svg"
		alt="xtreme admin" height="50px" style="border: none;height: 40px;margin-top: 10px;" /></a></td></tr><tr><td style="padding-bottom: 6px;"><h1
		style=" font-size: 14px; font-family: arial; margin: 0px; font-weight: bold;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important;padding-bottom: 6px; ">کاربر عزیز: </h1><p
		style="margin-top: 0px; color: #bbbbbb;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important;">'.$this->subject.' </p></td></tr><tr><td
		style="padding: 10px 0 20px 0;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important;">'.$this->text.'</td></tr><tr><td
		style=" border-top: 1px solid #f6f6f6;font-size:12px ; padding-top: 20px; color: #777;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important; ">توجه : این ایمیل به صورت خودکار فرستاده شده است ، بنابراین به آن جواب ندهید و از طریق
		ناحیه کاربری نتیجه را پیگیری نمایید. </td></tr></tbody></table></div><div style=" text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px; "><p>Powered by tarhpich.ir <br /><a href="javascript: void(0);"
		style="color: #b2b2b5; text-decoration: underline;font-family: Iransans,Tahoma,Verdana,Segoe,sans-serif !important;"></a></p></div></div></div></body></html>';
	}
/* 	private function _getToken()
    {
        $postData = array(
            'UserApiKey' => $this->APIKey,
            'SecretKey' => $this->SecretKey,
            'System' => 'php_rest_v_2_0'
        );
        $postString = json_encode($postData);

        $ch = curl_init($this->APIURL.$this->getApiTokenUrl());
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(

                'Content-Type: application/json'
                )
            );
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

            $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result);
        $resp = false;
        $IsSuccessful = '';
        $TokenKey = '';
        if (is_object($response)) {
            $IsSuccessful = $response->IsSuccessful;
            if ($IsSuccessful == true) {
                $TokenKey = $response->TokenKey;
                $resp = $TokenKey;
            } else {
                $resp = false;
            }
        }
        return $resp;
    }
	 public function ultraFastSend($data)
    {
        $token = $this->_getToken($this->APIKey, $this->SecretKey);
		//pr($data,true);
        if ($token != false) {
			$postData = $data;
            $url = $this->APIURL.$this->getAPIUltraFastSendUrl();
            $UltraFastSend = $this->executeCurl($postData, $url, $token);
            $object = json_decode($UltraFastSend);
            $result = false;
            if (is_object($object)) {
                $result = $object->Message;
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }
	*/

}



?>