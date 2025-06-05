<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BerkasPBJ;
use App\Models\User;
use App\Notifications\WaktuKontrakNotifikasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CheckExpiredContracts extends Command
{
    protected $signature = 'cek:tanggal-kontrak';
    protected $description = 'Cek tanggal kontrak dan pemeliharaan lalu kirim notifikasi ke user';

    public function handle()
    {
        $this->info('Mulai pengecekan tanggal kontrak dan pemeliharaan...');
        $today = Carbon::today();
        $berkasList = BerkasPBJ::all();
        $countNotifikasi = 0;

        foreach ($berkasList as $berkas) {
            // Cek kontrak yang akan selesai (H-3, H-2, H-1)
            if ($this->isApproaching($today, $berkas->tanggal_kontrak_selesai)) {
                if ($this->kirimNotifikasi($berkas, 'selesai_kontrak')) {
                    $countNotifikasi++;
                }
            }

            // Cek pemeliharaan yang akan selesai (H-3, H-2, H-1)
            if ($this->isApproaching($today, $berkas->tanggal_selesai_pemeliharaan)) {
                if ($this->kirimNotifikasi($berkas, 'selesai_pemeliharaan')) {
                    $countNotifikasi++;
                }
            }

            // Cek jika kontrak sudah selesai (H-0 atau lebih)
            if ($this->isOverdue($today, $berkas->tanggal_kontrak_selesai)) {
                if ($this->kirimNotifikasi($berkas, 'selesai_kontrak')) {
                    $countNotifikasi++;
                }
            }

            // Cek jika pemeliharaan sudah selesai (H-0 atau lebih)
            if ($this->isOverdue($today, $berkas->tanggal_selesai_pemeliharaan)) {
                if ($this->kirimNotifikasi($berkas, 'selesai_pemeliharaan')) {
                    $countNotifikasi++;
                }
            }
        }

        $this->info("Total {$countNotifikasi} notifikasi baru telah dikirim.");
        return Command::SUCCESS;
    }

    /**
     * Cek apakah tanggal target akan mendekati dalam 1-3 hari
     * Khusus untuk H-3, H-2, dan H-1
     */
    private function isApproaching($today, $tanggal)
    {
        if (empty($tanggal)) return false;

        $tanggalTarget = Carbon::parse($tanggal);
        $selisihHari = $today->diffInDays($tanggalTarget, false);

        // Hanya kembalikan true jika selisih adalah 1, 2, atau 3 hari dan tanggalTarget > today
        return $selisihHari > 0 && $selisihHari <= 3;
    }

    /**
     * Cek jika tanggal sudah jatuh tempo (hari ini atau sudah lewat)
     */
    private function isOverdue($today, $tanggal)
    {
        if (empty($tanggal)) return false;

        $tanggalTarget = Carbon::parse($tanggal);
        return $today->gte($tanggalTarget); // Memeriksa apakah hari ini >= tanggal target
    }

    /**
     * Kirim notifikasi jika belum ada notifikasi serupa untuk hari ini
     * @return bool True jika notifikasi dikirim, false jika sudah ada
     */
    private function kirimNotifikasi($berkas, $tipeNotifikasi)
    {
        $users = User::where('role', 'user')->get(); // ambil user 
        $notifikasiDikirim = false;

        foreach ($users as $user) {
            // Cek apakah notifikasi dengan tipe dan berkas yang sama sudah dikirim hari ini
            $notifikasiExists = DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->where('notifiable_type', get_class($user))
                ->where('created_at', '>=', Carbon::today())
                ->whereJsonContains('data->nomor_kontrak', $berkas->nomor_kontrak)
                ->whereJsonContains('data->type', in_array($tipeNotifikasi, ['mulai_kontrak', 'selesai_kontrak']) ? 'kontrak' : 'pemeliharaan')
                ->exists();

            if (!$notifikasiExists) {
                $user->notify(new WaktuKontrakNotifikasi($berkas, $tipeNotifikasi));
                $this->info("Notifikasi {$tipeNotifikasi} dikirim ke {$user->name} untuk kontrak {$berkas->nomor_kontrak}");
                $notifikasiDikirim = true;
            } else {
                $this->info("Skip: Notifikasi {$tipeNotifikasi} untuk {$user->name} (kontrak {$berkas->nomor_kontrak}) sudah ada hari ini");
            }
        }

        return $notifikasiDikirim;
    }
}
