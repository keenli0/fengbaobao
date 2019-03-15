<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 2018/10/17
 * Time: 16:30
 */
function getClientIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if (REQ_CUSTOMER_ID != null)
        $ip = REQ_CUSTOMER_ID;
    return $ip;
}