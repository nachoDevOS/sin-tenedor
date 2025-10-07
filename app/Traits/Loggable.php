<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

trait Loggable
{
    /**
     * Registra un error detallado en el log.
     *
     * @param \Throwable $th La excepción capturada.
     * @param \Illuminate\Http\Request $request La solicitud HTTP actual.
     * @param string $context Un mensaje de contexto para identificar la operación que falló.
     */
    protected function logError(Throwable $th, Request $request)
    {
        // Obtener información sobre quién llamó a esta función (controlador y método)
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $caller = $backtrace[1]; // El índice 1 contiene la información del llamador

        // Construir el contexto automático: App\Http\Controllers\VaultController::details_store
        $autoContext = $caller['class'] . '::' . $caller['function'];

        // Si se proporciona un contexto manual, se añade al automático.
        $finalContext = $autoContext;

        $logMessage = [
            "🚨 ERROR CRÍTICO - {$finalContext}",
            "==================================================",
            "📋 INFORMACIÓN GENERAL:",
            "   - Usuario: " . (Auth::check() ? Auth::user()->name . ' (ID: ' . Auth::id() . ')' : 'No autenticado'),
            "   - Fecha/Hora: " . now()->format('d/m/Y H:i:s'),
            "   - IP: " . $request->ip(),
            "   - URL: " . $request->fullUrl(),
            "--------------------------------------------------",
            "🔍 DETALLES DEL ERROR:",
            "   - Mensaje: " . $th->getMessage(),
            "   - Archivo: " . $th->getFile(),
            "   - Línea: " . $th->getLine(),
            "--------------------------------------------------",
            "📊 DATOS DE LA SOLICITUD (Payload):",
        ];

        // Obtener todos los datos de la solicitud, excluyendo campos sensibles.
        $requestData = $request->except(['password', 'password_confirmation', '_token', '_method']);
        if (!empty($requestData)) {
            foreach ($requestData as $key => $value) {
                // Si el valor es un array, lo convertimos a JSON para una mejor visualización.
                $formattedValue = is_array($value) ? json_encode($value) : $value;
                $logMessage[] = "   - {$key}: {$formattedValue}";
            }
        } else {
            $logMessage[] = "   - (No hay datos de entrada)";
        }
        $logMessage[] = "==================================================";
        $logMessage[] = "📄 STACK TRACE:";
        $logMessage[] = $th->getTraceAsString();
        $logMessage[] = "==================================================";

        Log::error(implode(PHP_EOL, $logMessage));
    }
}