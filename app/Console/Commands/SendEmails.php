<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\MusicReportMail;
use Mail;
use DB;
use Http;
use App\Mail\SslExpiryMail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendEmails:Cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $domains = [
            'bibleproduction.olybible.com',
            'services.olybible.com',
            'banner.olybible.com',
            'nearbychurch.olybible.com',
            'readingplans.olybible.com',
            'development.olybible.com',
            'bibleprayers.olybible.com',
            'biblechats.olybible.com',
            'biblequiz.olybible.com',
            'biblequotesmaker.biblereadingzone.com',
            'bibleproductionapi.biblereadingzone.com',
            'biblefonts.olybible.com',
            'bibleliteversionapi.biblereadingzone.com',
            'bibledev.olybible.com',
            'musicplayer.olybible.com',
            'biblev2.olybible.com',
            'community.biblereadingzone.com',
            'biblepuzzlegame.olybible.com',
            'olybible.com',
            'commentrybible.olybible.com',
            'biblereadingzone.com',
            'admin.olybible.com',
            'bibleticket.olybible.com',
            'audiobible.olybible.com'
        ];
        
        $sslReport = [];

        foreach ($domains as $domain) {
            $expiry = $this->getSslExpiry($domain);
        
            if (!$expiry) continue;
        
            $status = $this->getSslStatus($expiry);
        
            $sslReport[] = [
                'domain' => $domain,
                'expiry' => $expiry,
                'days_left' => now()->diffInDays($expiry),
                'status' => $status['label'],
            ];
        }

        // Sort by days left (ascending - urgent first)
        usort($sslReport, function($a, $b) {
            return $a['days_left'] <=> $b['days_left'];
        });
        
        // Check if the current time is exactly 9:45:00 AM
        $currentTime = \Carbon\Carbon::parse(now()->addHours(5)->addMinutes(30))->format('h:i A');
        $sendTime = \Carbon\Carbon::parse('12:47 PM')->format('h:i A');
        
        if ($currentTime == $sendTime) {
            Mail::bcc(['ravindhiran@skyraan.com', 'gunasekar@skyraan.com'])->send(new SslExpiryMail($sslReport));
            Mail::bcc(['ravindhiran@skyraan.com', 'gunasekar@skyraan.com'])->send(new MusicReportMail());
        }
    }
    
    public static function getSslExpiry($domain)
    {
        $stream = stream_context_create([
            "ssl" => [
                "capture_peer_cert" => true,
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ]);
    
        $read = @stream_socket_client(
            "ssl://{$domain}:443",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $stream
        );
    
        if (!$read) {
            return null;
        }
    
        $cert = stream_context_get_params($read);
        $certInfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);
    
        return date('Y-m-d', $certInfo['validTo_time_t']);
    }

    
    public static function getSslStatus($expiryDate)
    {
        $days = now()->diffInDays($expiryDate, false);
    
        if ($days <= 30) {
            return ['label' => 'URGENT', 'color' => 'red'];
        } elseif ($days <= 45) {
            return ['label' => 'WARNING', 'color' => 'orange'];
        }
        return ['label' => 'OK', 'color' => 'green'];
    }

}
