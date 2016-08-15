<?php
phpinfo();
$connection = new Mongo();
$db = $connection->test;
$data = $db->ba;
$arr = array('t'=>1232,'b'=>4562);
//$data->insert($arr);
$obj = $data->findOne(array('t'=>1232));
echo $data->count();
$cursor = $data->find(array('t'=>1232));

foreach ($cursor as $doc) {
    var_dump($doc);
}