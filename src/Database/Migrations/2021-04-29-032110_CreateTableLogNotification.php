<?php

namespace Esoftdream\Notification\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLogNotification extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "`notification_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID Notifikasi'",
            "`notification_type` ENUM('wa','email','sms','telegram') NULL DEFAULT NULL COMMENT 'Jenis Notifikasi'",
            "`notification_session` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Session Ketika Mengirim Pesan'",
            "`notification_content` TEXT NOT NULL COMMENT 'Isi Pesan'",
            "`notification_sender` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Pengirim Pesan'",
            "`notification_receiver` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Penerima Pesan'",
            "`notification_response` TEXT NOT NULL COMMENT 'Respon Pengiriman Pesan'",
            "`notification_status` ENUM('Terkirim','Gagal') NOT NULL DEFAULT 'Gagal' COMMENT 'Status Pengiriman Pesan'",
            "`notification_datetime` DATETIME NOT NULL COMMENT 'Tanggal Pengiriman Pesan'",
        ]);

        $this->forge->addKey('notification_id', true);
        $this->forge->createTable('log_notification', true);
    }

    public function down()
    {
        $this->forge->dropTable('log_notification');
    }
}
