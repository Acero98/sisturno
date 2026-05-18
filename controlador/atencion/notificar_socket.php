<?php

$datos = [
    'fecha' => date('Y-m-d H:i:s'),
    'accion' => 'ticket_actualizado'
];

$payload = json_encode($datos);

//$ch = curl_init('http://localhost:3000/notificar');
$ch = curl_init('http://192.168.100.120:3000/notificar');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);
?>