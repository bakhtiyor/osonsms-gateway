<?php

namespace OsonSMS\SMSGateway;


use OsonSMS\SMSGateway\Models\OsonSMSLog;

class SMSGateway
{
    public static function SendRequest($type, $url, $parameters)
    {
        $curl = curl_init();
        $data = http_build_query ($parameters);
        if ($type == "GET") {
            curl_setopt ($curl, CURLOPT_URL, "$url?$data");
        }else if($type == "POST"){
            curl_setopt ($curl, CURLOPT_URL, $url);
            curl_setopt ($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $type
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $result = array();
        if ($err) {
            $result['error'] = 1;
            $result['msg'] = $err;
        } else {
            $res = json_decode($response);
            if (isset($res->error)){
                $result['error'] = 1;
                $result['msg'] = "Error Code: ". $res->error->code . " Message: " . $res->error->msg;
            }else{
                $result['error'] = 0;
                $result['msg'] = $response;
            }
        }
        return $result;
    }

    public static function Send($phonenumber, $message, $txn_id)
    {
        $OsonSMSLog = new OsonSMSLog();
        $OsonSMSLog->login = config('smsgateway.login');
        $OsonSMSLog->sender_name = config('smsgateway.sender_name');
        $OsonSMSLog->message = $message;
        $OsonSMSLog->phonenumber = $phonenumber;
        $OsonSMSLog->save();

        $dlm = ";";
        $str_hash = hash('sha256',$txn_id.$dlm.config('smsgateway.login').$dlm.config('smsgateway.sender_name').$dlm.$phonenumber.$dlm.config('smsgateway.hash'));
        $parameters = array(
            "from" => config('smsgateway.sender_name'),
            "phone_number" => $phonenumber,
            "msg" => $message,
            "str_hash" => $str_hash,
            "txn_id" => $txn_id,
            "login"=>config('smsgateway.login'),
        );
        $result = static::SendRequest("GET", config('smsgateway.server_url').'/sendsms_v1.php', $parameters);
        $_OsonSMSLog = OsonSMSLog::findOrFail($OsonSMSLog->id);
        $_OsonSMSLog->server_response = $result['msg'];
        if ($result['error']==0){
            $response = json_decode($result['msg']);
            $_OsonSMSLog->msgid = $response->msg_id;
            $_OsonSMSLog->is_sent = 1;
        }else{
            $_OsonSMSLog->is_sent = 0;
        }
        $_OsonSMSLog->update();
        if ((isset($result['error']) && $result['error'] == 0))
            return true;
        else
            return false;
    }

    public static function getBalance()
    {
        $dlm = ";";
        $txn_id = uniqid('osonsms_laravel_', true);
        $str_hash = hash('sha256',$txn_id.$dlm.config('smsgateway.login').$dlm.config('smsgateway.hash'));
        $parameters = array(
            "str_hash" => $str_hash,
            "txn_id" => $txn_id,
            "login"=>config('smsgateway.login'),
        );
        $result = static::SendRequest("GET", config('smsgateway.server_url').'/check_balance.php', $parameters);
        if ((isset($result['error']) && $result['error'] == 0)){
            $response = json_decode($result['msg']);
            return $response->balance;
        }else
            return 0;
    }
}
