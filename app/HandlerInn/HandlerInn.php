<?php

namespace App\HandlerInn;

use Carbon\Carbon;
use GuzzleHttp\Client;

class HandlerInn
{
    private $url = "https://statusnpd.nalog.ru/api/v1/tracker/taxpayer_status";
    private $client;
    private $mnog1 = [
        7,2,4,10,3,5,9,4,6,8,0
    ];
    private $mnog2 = [
        3,7,2,4,10,3,5,9,4,6,8,0
    ];
    private $db;

    public function __construct()
    {
        $this->client = new Client();
        $this->db = resolve(HandlerDB::class);
    }

    function checkInn($inn) {
        if(!$this->validateInn($inn)) {
            return "Неверный ИНН";
        };
        $date = Carbon::now();
        if($record = $this->db->check($inn)) {
            if(Carbon::createFromFormat("Y-m-d\TH:i:s", $record->data)->addDay()->gt($date)) {
                return $record->info;
            }
        }
        $this->requestAndSave($inn, $date);
    }

    function requestAndSave($inn, $date) {
        $q = $this->client->request('POST', $this->url, [
            'json' => [
                'inn' => $inn,
                'requestDate' => date('Y-m-d')
            ]
        ]);
        $data = json_decode($q->getBody()->getContents(), true);
        $this->db->save($inn, $data['message'], $date->format("Y-m-d\TH:i:s"));
        return $data['message'];
    }

    function validateInn($inn) {
        if(strlen($inn) != 12) {
            return false;
        }
        for($i = 1; $i < 3;$i++) {
            $num[] = $this->logic($inn,$i);
        }
        return $this->checkControlNum($num, $inn);
    }

    function logic($inn, $num) {
        $sum = 0;
        foreach ($this->{"mnog".$num} as $key => $item) {
            $sum += $item * $inn[$key];
        }
        $control = $sum%11;
        if($control > 9) {
            $control = $control%10;
        }
        return $control;
    }

    function checkControlNum(array $array, $inn) {
        if($array[0] == (int)$inn[10] && $array[1] == (int)$inn[11]) {
            return true;
        }
        return false;
    }

}
