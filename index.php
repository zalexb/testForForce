<?php
include_once 'Classes.php';

const DB_NAME = "forforce";
const DB_HOST = "localhost";
const DB_PASSWD = "";
const DB_USER = "root";


$test = new Factory();
$user = $test->getUserById(1);
echo '<pre>';
//print_r($user);
//var_dump($test->createUser('test user','23-12-1992'));
//var_dump($test->makeDeposit(380,67,2316996,40));
//var_dump($test->addPhoneToUser(2000,380,67,5316396));
//var_dump($test->deleteUser(2000));



