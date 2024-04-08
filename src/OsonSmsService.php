<?php

namespace OsonSMS\SMSGateway;


use GuzzleHttp\Exception\ClientException;
use JsonException;
use OsonSMS\SMSGateway\Models\OsonSMSLog;
use GuzzleHttp\Client;
use RuntimeException;

class OsonSmsService
{
    private string $serverUrl;
    private string $smsLogin;
    private string $passSaltHash;
    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->serverUrl = config('smsgateway.server_url');
        $this->smsLogin = config('smsgateway.login');
        $this->passSaltHash = config('smsgateway.pass_salt_hash');
    }

    public function sendSMS(string $senderName, string $phonenumber, string $message, string $txnId): int
    {
        $OsonSMSLog = new OsonSMSLog();
        $OsonSMSLog->login = $this->smsLogin;
        $OsonSMSLog->sender_name = $senderName;
        $OsonSMSLog->message = $message;
        $OsonSMSLog->phonenumber = $phonenumber;

        $dlm = ";";
        $strHash = hash('sha256',$txnId.$dlm.$this->smsLogin.$dlm.$senderName.$dlm.$phonenumber.$dlm.$this->passSaltHash);
        $queryParams = [
            "from" => $senderName,
            "phone_number" => $phonenumber,
            "msg" => $message,
            "str_hash" => $strHash,
            "txn_id" => $txnId,
            "login" => $this->smsLogin,
        ];
        try {
            $response = $this->httpClient->get($this->serverUrl . '/sendsms_v1.php', ['query' => $queryParams]);
            $responseBody = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
            $OsonSMSLog->server_response = $response->getBody()->getContents();
            $OsonSMSLog->msgid = $responseBody->msg_id;
            $OsonSMSLog->is_sent = 1;
            $OsonSMSLog->save();
            return $responseBody->msg_id;
        } catch (ClientException $exception) {
            $responseBody = json_decode($exception->getResponse()->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
            $OsonSMSLog->server_response = $responseBody->error->msg;
            $OsonSMSLog->is_sent = 0;
            $OsonSMSLog->save();
            throw new RuntimeException($exception->getMessage());
        } catch (JsonException $jsonException) {
            throw new RuntimeException($jsonException->getMessage());
        }
    }

    public function getBalance(): float
    {
        $dlm = ";";
        $txnId = uniqid('osonsms_laravel_', true);
        $strHash = hash('sha256',$txnId.$dlm.$this->smsLogin.$dlm.$this->passSaltHash);
        $queryParams = array(
            "str_hash" => $strHash,
            "txn_id" => $txnId,
            "login"=>$this->smsLogin,
        );
        try {
            $response = $this->httpClient->get($this->serverUrl . '/check_balance.php', ['query' => $queryParams]);
            $responseBody = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
            return $responseBody->balance;
        } catch (ClientException $exception) {
            throw new RuntimeException($exception->getMessage());
        } catch (JsonException $jsonException) {
            throw new RuntimeException($jsonException->getMessage());
        }
    }

    public function getSMSStatus(int $msgId): string
    {
        $dlm = ";";
        $txnId = uniqid('osonsms_laravel_', true);
        $strHash = hash('sha256',$this->smsLogin.$dlm.$txnId.$dlm.$this->passSaltHash);
        $queryParams = array(
            "str_hash" => $strHash,
            "txn_id" => $txnId,
            "login"=>$this->smsLogin,
            "msg_id"=>$msgId,
        );
        try {
            $response = $this->httpClient->get($this->serverUrl . '/query_sms.php', ['query' => $queryParams]);
            $responseBody = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
            return $responseBody->message_state;
        } catch (ClientException $exception) {
            throw new RuntimeException($exception->getMessage());
        } catch (JsonException $jsonException) {
            throw new RuntimeException($jsonException->getMessage());
        }
    }
}
