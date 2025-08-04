<?php

namespace Esoftdream\Notification\Libraries;

use CodeIgniter\Database\BaseConnection;
use Config\Database;
use Config\Services;
use Esoftdream\WaOne\WaOne;
use Exception;

class Notification
{
    /**
     * Database connection
     *
     * @var BaseConnection
     */
    private $db;

    public function __construct(BaseConnection $db)
    {
        $this->db = $db;
    }

    /**
     * Send notification WhatsApp via Waone
     *
     * @throws Exception Jika nomor telepon tidak valid
     */
    public function sendWaone(string $receiver, string $message)
    {
        $waone_url   = Services::config()->get('waone.url');
        $waone_token = Services::config()->get('waone.token');

        $waone = new WaOne($waone_url, $waone_token);

        try {
            $receiver = format_mobilephone($receiver);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $response = $waone->send($message, $receiver);

        $res = json_decode($response);

        $log = [
            'notification_type'     => 'wa',
            'notification_session'  => session_id(),
            'notification_content'  => $message,
            'notification_sender'   => '',
            'notification_receiver' => $receiver,
            'notification_response' => $response,
            'notification_status'   => @$res->data->message_status == 'success' ? 'Terkirim' : 'Gagal',
            'notification_datetime' => @$res->data->message_updated_datetime ? date('Y-m-d H:i:s', strtotime((string) $res->data->message_updated_datetime)) : date('Y-m-d H:i:s'),
        ];

        $this->db->table('log_notification')->insert($log);

        return $response;
    }
}
