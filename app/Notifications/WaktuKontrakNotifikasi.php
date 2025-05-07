<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class WaktuKontrakNotifikasi extends Notification
{
    use Queueable;

    protected $berkasPBJ;
    protected $tipeNotifikasi;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $berkasPBJ
     * @param  string $tipeNotifikasi ('mulai_kontrak', 'selesai_kontrak', 'mulai_pemeliharaan', 'selesai_pemeliharaan')
     */
    public function __construct($berkasPBJ, $tipeNotifikasi)
    {
        $this->berkasPBJ = $berkasPBJ;
        $this->tipeNotifikasi = $tipeNotifikasi;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = new MailMessage;

        // Tentukan pesan berdasarkan tipe notifikasi dan selisih hari
        $pesan = $this->getPesanBerdasarkanSelisihHari();

        switch ($this->tipeNotifikasi) {
            case 'mulai_kontrak':
                $message->line('Kontrak telah dimulai.')
                    ->line('Nomor Kontrak: ' . $this->berkasPBJ->nomor_kontrak)
                    ->line('Nama Kontrak: ' . $this->berkasPBJ->nama_kontrak)
                    ->line('Tanggal Mulai: ' . $this->berkasPBJ->tanggal_kontrak_mulai);
                break;
            case 'selesai_kontrak':
                $message->line($pesan)
                    ->line('Nomor Kontrak: ' . $this->berkasPBJ->nomor_kontrak)
                    ->line('Nama Kontrak: ' . $this->berkasPBJ->nama_kontrak)
                    ->line('Tanggal Selesai: ' . $this->berkasPBJ->tanggal_kontrak_selesai);
                break;
            case 'mulai_pemeliharaan':
                $message->line('Pemeliharaan telah dimulai.')
                    ->line('Nomor Kontrak: ' . $this->berkasPBJ->nomor_kontrak)
                    ->line('Nama Kontrak: ' . $this->berkasPBJ->nama_kontrak)
                    ->line('Tanggal Mulai Pemeliharaan: ' . $this->berkasPBJ->tanggal_mulai_pemeliharaan);
                break;
            case 'selesai_pemeliharaan':
                $message->line($pesan)
                    ->line('Nomor Kontrak: ' . $this->berkasPBJ->nomor_kontrak)
                    ->line('Nama Kontrak: ' . $this->berkasPBJ->nama_kontrak)
                    ->line('Tanggal Selesai Pemeliharaan: ' . $this->berkasPBJ->tanggal_selesai_pemeliharaan);
                break;
        }

        return $message->action('Lihat Detail', route('berkas_pbj.update', ['nomor_kontrak' => $this->berkasPBJ->nomor_kontrak]))
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // Dapatkan pesan berdasarkan selisih hari
        $pesan = $this->getPesanBerdasarkanSelisihHari();

        $messages = [
            'mulai_kontrak' => 'Waktu Kontrak "' . $this->berkasPBJ->nama_kontrak . '" telah dimulai.',
            'selesai_kontrak' => 'Kontrak "' . $this->berkasPBJ->nama_kontrak . '" ' . $pesan,
            'mulai_pemeliharaan' => 'Waktu Pemeliharaan untuk kontrak "' . $this->berkasPBJ->nama_kontrak . '" telah dimulai.',
            'selesai_pemeliharaan' => 'Pemeliharaan untuk kontrak "' . $this->berkasPBJ->nama_kontrak . '" ' . $pesan,
        ];

        return [
            'id' => $this->berkasPBJ->id,
            'nomor_kontrak' => $this->berkasPBJ->nomor_kontrak,
            'nama_kontrak' => $this->berkasPBJ->nama_kontrak,
            'tanggal' => match ($this->tipeNotifikasi) {
                'mulai_kontrak' => $this->berkasPBJ->tanggal_kontrak_mulai,
                'selesai_kontrak' => $this->berkasPBJ->tanggal_kontrak_selesai,
                'mulai_pemeliharaan' => $this->berkasPBJ->tanggal_mulai_pemeliharaan,
                'selesai_pemeliharaan' => $this->berkasPBJ->tanggal_selesai_pemeliharaan,
            },
            'message' => $messages[$this->tipeNotifikasi],
            'type' => in_array($this->tipeNotifikasi, ['mulai_kontrak', 'selesai_kontrak']) ? 'kontrak' : 'pemeliharaan',
            'url' => route('berkas_pbj.update', ['nomor_kontrak' => $this->berkasPBJ->nomor_kontrak])
        ];
    }

    /**
     * Mendapatkan pesan berdasarkan selisih hari
     */
    private function getPesanBerdasarkanSelisihHari()
    {
        $today = Carbon::today();
        $tanggalTarget = null;

        if ($this->tipeNotifikasi == 'selesai_kontrak') {
            $tanggalTarget = Carbon::parse($this->berkasPBJ->tanggal_kontrak_selesai);
        } elseif ($this->tipeNotifikasi == 'selesai_pemeliharaan') {
            $tanggalTarget = Carbon::parse($this->berkasPBJ->tanggal_selesai_pemeliharaan);
        }

        if (!$tanggalTarget) {
            return '';
        }

        $selisihHari = $today->diffInDays($tanggalTarget, false);

        if ($selisihHari <= 0) {
            return 'sudah selesai.';
        } elseif ($selisihHari == 1) {
            return 'akan selesai dalam 1 hari lagi.';
        } elseif ($selisihHari == 2) {
            return 'akan selesai dalam 2 hari lagi.';
        } elseif ($selisihHari == 3) {
            return 'akan selesai dalam 3 hari lagi.';
        } else {
            return 'akan selesai dalam ' . $selisihHari . ' hari lagi.';
        }
    }
}
