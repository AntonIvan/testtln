<?php


namespace App\HandlerInn;


class HandlerDB
{
    private $table = "users_inns";

    function save($inn, $info, $date) {
        \DB::table($this->table)->insert([
            "inn" => $inn,
            "info" => $info,
            "data" => $date
        ]);
    }

    function read() {

    }

    function check($inn) {
        return \DB::table($this->table)->where("inn", $inn)->first();
    }
}
