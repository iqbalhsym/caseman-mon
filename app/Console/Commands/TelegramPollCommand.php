<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramPollCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Polls Telegram API for new messages (used instead of Webhooks)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) {
            $this->error('TELEGRAM_BOT_TOKEN not set in .env');
            return;
        }

        $this->info("Polling Telegram for updates... Press Ctrl+C to stop.");

        $offset = 0;
        
        while (true) {
            try {
                $response = Http::timeout(35)->get("https://api.telegram.org/bot{$token}/getUpdates", [
                    'offset' => $offset,
                    'timeout' => 30, // Long polling
                ]);

                if ($response->successful()) {
                    $updates = $response->json('result');
                    
                    foreach ($updates as $update) {
                        $offset = $update['update_id'] + 1; // Update offset to acknowledge receipt
                        
                        if (isset($update['message']['text']) || isset($update['message']['contact'])) {
                            $this->processMessage($update['message'], $token);
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error polling Telegram: " . $e->getMessage());
                sleep(5); // Wait before retrying
            }
        }
    }

    private function processMessage($message, $token)
    {
        $chatId = $message['chat']['id'] ?? null;
        if (!$chatId) return;

        // Check if the message contains a contact
        if (isset($message['contact'])) {
            $contact = $message['contact'];
            
            // Security: Ensure the user is sharing their OWN contact
            if (isset($message['from']['id']) && isset($contact['user_id']) && $message['from']['id'] != $contact['user_id']) {
                $this->sendMessage($chatId, "❌ Mohon bagikan kontak pribadi Anda, bukan kontak orang lain.", $token);
                return;
            }

            $phone = $contact['phone_number'] ?? '';
            // Clean phone number: remove non-numeric
            $cleanPhone = preg_replace('/\D/', '', $phone);
            
            // Normalize to local format (remove 62 or +62 prefix)
            if (str_starts_with($cleanPhone, '62')) {
                $cleanPhone = substr($cleanPhone, 2);
            }
            
            // Remove leading zero if exists
            $cleanPhone = ltrim($cleanPhone, '0');

            // Search user in DB: match suffix to handle different formats (08..., 628..., +628...)
            $user = User::where('phone', 'LIKE', '%' . $cleanPhone)->first();

            if ($user) {
                $user->telegram_chat_id = $chatId;
                $user->save();
                
                $msg = "✅ <b>Berhasil!</b> Nomor HP Anda cocok dengan profil <b>" . htmlspecialchars($user->name) . "</b>. \n\nMulai sekarang, Anda akan menerima notifikasi otomatis di Telegram ini.";
                $this->sendMessage($chatId, $msg, $token, true); // true to remove keyboard
                $this->info("Linked Chat ID $chatId to User {$user->id} ({$user->name}) via Contact Sharing");
            } else {
                $msg = "❌ <b>Gagal:</b> Nomor telepon (" . $phone . ") Anda tidak ditemukan di dalam sistem Casemanager RSUI. Pastikan Anda telah menyimpan nomor telepon Anda di menu Profil di aplikasi web.";
                $this->sendMessage($chatId, $msg, $token, true);
            }
            return;
        }

        $text = $message['text'] ?? '';
        if (str_starts_with($text, '/start')) {
            $parts = explode(' ', $text);
            if (count($parts) > 1) {
                $token = $parts[1];
                try {
                    $userId = base64_decode($token);
                    $user = User::find($userId);

                    if ($user) {
                        $user->telegram_chat_id = $chatId;
                        $user->save();
                        
                        $msg = "✅ <b>Otomatis Terhubung!</b> Akun Telegram Anda telah berhasil ditautkan dengan profil <b>" . htmlspecialchars($user->name) . "</b>.\n\nSekarang Anda akan menerima notifikasi Casemanager di sini.";
                        $this->sendMessage($chatId, $msg, $token, true);
                        $this->info("Automatically linked Chat ID $chatId to User $userId via token.");
                        return;
                    }
                } catch (\Exception $e) {
                    $this->error("Error decoding token: " . $e->getMessage());
                }
            }

            $msg = "👋 Halo! Ini adalah Bot Keamanan Casemanager RSUI.\n\nUntuk menghubungkan akun, silakan tekan tombol di bawah ini untuk membagikan kontak Anda, atau klik ulang link 'Hubungkan Telegram' di aplikasi web.";
            $this->requestContact($chatId, $msg, $token);
        }
    }

    private function requestContact($chatId, $message, $token)
    {
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => 'Bagikan Kontak Saya 📱', 'request_contact' => true]
                    ]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ]);
    }

    private function sendMessage($chatId, $message, $token, $removeKeyboard = false)
    {
        $payload = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];
        
        if ($removeKeyboard) {
            $payload['reply_markup'] = json_encode([
                'remove_keyboard' => true
            ]);
        }

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", $payload);
    }
}
