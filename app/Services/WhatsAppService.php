<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected Client $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function enviar(string $telefono, string $mensaje): bool
    {
        try {
            $numero = $this->normalizarTelefono($telefono);

            $this->twilio->messages->create("whatsapp:{$numero}", [
                'from' => config('services.twilio.whatsapp_from'),
                'body' => $mensaje,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("WhatsApp error [{$telefono}]: " . $e->getMessage());
            return false;
        }
    }

    private function normalizarTelefono(string $tel): string
    {
        $tel = preg_replace('/\D/', '', $tel); // quitar todo lo que no sea número
        if (strlen($tel) === 10) $tel = '1' . $tel; // agregar código RD/USA
        return '+' . $tel;
    }

    public function enviarConPlantilla(string $telefono, string $templateSid, array $variables): bool
    {
        try {
            $numero = $this->normalizarTelefono($telefono);

            $this->twilio->messages->create("whatsapp:{$numero}", [
                'from'             => config('services.twilio.whatsapp_from'),
                'contentSid'       => $templateSid,
                'contentVariables' => json_encode($variables),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("WhatsApp template error [{$telefono}]: " . $e->getMessage());
            return false;
        }
    }
}
