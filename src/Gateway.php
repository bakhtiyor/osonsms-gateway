<?php

namespace Osonsms\Gateway;

use Osonsms\Gateway\Models\OsonSMSLog;

class Gateway
{
    public static function SendRequest($type, $url, $parameters)
    {
        $curl = curl_init();
        $data = http_build_query ($parameters);
        echo "$url?$data";
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

    public static function Send($phonenumber, $message)
    {

        $OsonSMSLog = new OsonSMSLog();
        $OsonSMSLog->login = config('gateway.login');
        $OsonSMSLog->sender_name = config('gateway.sender_name');
        $OsonSMSLog->message = $message;
        $OsonSMSLog->phonenumber = $phonenumber;
        $OsonSMSLog->save();

        $dlm = ";";
        $login =
        $txn_id = "osonsms_laravel_".$OsonSMSLog->id;
        $str_hash = hash('sha256',$txn_id.$dlm.config('gateway.login').$dlm.config('gateway.sender_name').$dlm.$phonenumber.$dlm.config('gateway.hash'));
        $parameters = array(
            "from" => config('gateway.sender_name'),
            "phone_number" => $phonenumber,
            "msg" => $message,
            "str_hash" => $str_hash,
            "txn_id" => $txn_id,
            "login"=>config('gateway.login'),
        );
        return static::SendRequest("GET", config('gateway.server_url'), $parameters);
    }
}
