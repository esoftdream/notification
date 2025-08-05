<?php

namespace Esoftdream\Notification\Libraries;

use CodeIgniter\Database\BaseConnection;
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

    /**
     * WaOne URL
     *
     * @var string
     */
    private $waone_url;

    /**
     * WaOne Token
     *
     * @var string
     */
    private $waone_token;

    public function __construct(BaseConnection $db, string $waoneUrl, string $waoneToken)
    {
        $this->db          = $db;
        $this->waone_url   = $waoneUrl;
        $this->waone_token = $waoneToken;

        helper('number');
    }

    /**
     * Mengirim notifikasi WhatsApp ke penerima yang ditentukan.
     *
     * @param string $receiver Nomor telepon penerima dalam format internasional.
     * @param string $message  Pesan yang akan dikirim.
     * @param string $vendor   Vendor layanan pengiriman pesan, default 'waone'.
     *
     * @return mixed Respon dari pengiriman pesan melalui vendor yang dipilih.
     *
     * @throws Exception Jika vendor yang dipilih tidak didukung.
     */
    public function sendWhatsapp(string $receiver, string $message, string $vendor = 'waone')
    {
        switch ($vendor) {
            case 'waone':
                return $this->vendorWaone($receiver, $message);

            default:
                throw new Exception("Unsupported vendor: {$vendor}");
        }
    }

    /**
     * Send notification WhatsApp via WaOne
     *
     * @throws Exception Jika nomor telepon tidak valid
     */
    private function vendorWaone(string $receiver, string $message)
    {
        $waone = new WaOne($this->waone_url, $this->waone_token);

        try {
            $receiver = format_mobilephone($receiver);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
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
