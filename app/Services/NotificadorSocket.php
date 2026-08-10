<?php

require_once __DIR__ . '/../../config.php';

class NotificadorSocket
{
    public function notificar(array $datos = []): void
    {
        $datos['fecha'] = $datos['fecha'] ?? date('Y-m-d H:i:s');
        $datos['accion'] = $datos['accion'] ?? 'ticket_actualizado';

        $curl = curl_init(SOCKETURL . '/notificar');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        curl_exec($curl);
        curl_close($curl);
    }
}
