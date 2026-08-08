<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MonitorSO extends Command
{
    protected $signature = 'so:monitor';
    protected $description = 'Monitorea CPU, memoria y disco del sistema operativo en tiempo real';

    public function handle()
    {
        $this->info("=== MONITOREO DE SISTEMA OPERATIVO ===");

        while (true) {
            $cpu = sys_getloadavg()[0];
            $freeMemory = shell_exec("free -m | awk 'NR==2{printf \"%.2f\", $3/$2 * 100}'");
            $disk = shell_exec("df / | awk 'NR==2{print $5}'") ?: "0%";

            $mensaje = "CPU Load: {$cpu} | Memoria Usada: " . trim($freeMemory) . "% | Disco Usado: " . trim($disk);

            $this->line($mensaje);
            Log::channel('single')->info($mensaje);

            sleep(5);
        }
    }
}