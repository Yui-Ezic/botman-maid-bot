<?php


namespace App\Services\Messages;


interface MessageService
{
    /**
     * Returns message string by key
     *
     * @param $key
     * @param array $args
     *
     * @return mixed
     */
    public function getMessage($key, $args = []);
}