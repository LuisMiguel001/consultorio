<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\CuentaPaciente;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuentaPacienteController extends Controller
{
    public function index()
    {
        $consultorio = Auth::user()->consultorio;

        $cuentas = CuentaPaciente::with([
            'paciente',
            'consulta',
            'detalles.servicio'
        ])
            ->where(
                'consultorio_id',
                $consultorio->id
            )
            ->latest()
            ->get();

        return view(
            'cuentas.index',
            compact('cuentas')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VER DETALLE
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $consultorio = Auth::user()->consultorio;

        $cuenta = CuentaPaciente::with([
            'paciente',
            'consulta',
            'detalles.servicio'
        ])
            ->where(
                'consultorio_id',
                $consultorio->id
            )
            ->findOrFail($id);

        return view(
            'cuentas.show',
            compact('cuenta')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COBRAR
    |--------------------------------------------------------------------------
    */

    public function cobrar(Request $request)
    {
        $consultorio = Auth::user()->consultorio;

        $request->validate([
            'cuenta_id' => 'required',
            'metodo_pago' => 'required'
        ]);

        $cuenta = CuentaPaciente::where(
            'consultorio_id',
            $consultorio->id
        )
            ->findOrFail($request->cuenta_id);

        if ($cuenta->estado == 'pagado') {

            return back()->with(
                'error',
                'La factura ya fue pagada.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SI USA CAJA
        |--------------------------------------------------------------------------
        */

        if ($consultorio->usar_caja) {

            $caja = Caja::where(
                'consultorio_id',
                $consultorio->id
            )
                ->where(
                    'estado',
                    'abierta'
                )
                ->first();

            if (!$caja) {

                return back()->with(
                    'error',
                    'No hay caja abierta.'
                );
            }

            MovimientoCaja::create([

                'consultorio_id' => $consultorio->id,

                'caja_id' => $caja->id,

                'tipo' => 'ingreso',

                'concepto' =>
                'Cobro cuenta paciente #' .
                    $cuenta->id,

                'monto' => $cuenta->total,

                'paciente_id' =>
                $cuenta->paciente_id,

                'metodo_pago' =>
                $request->metodo_pago,

                'usuario_id' => Auth::id()
            ]);
        }

        $cuenta->update([
            'estado' => 'pagado'
        ]);

        return back()->with(
            'success',
            'Cobro realizado correctamente.'
        );
    }

    /*
|--------------------------------------------------------------------------
| VERIFICAR CREDENCIALES DEL DOCTOR
|--------------------------------------------------------------------------
*/
    public function verificarDoctor(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'cuenta_id' => 'required|integer',
        ]);

        $consultorio = Auth::user()->consultorio;

        // La cuenta debe existir, pertenecer al consultorio y NO estar pagada
        $cuenta = CuentaPaciente::where('consultorio_id', $consultorio->id)
            ->findOrFail($request->cuenta_id);

        if ($cuenta->estado === 'pagado') {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'La factura ya fue pagada y no puede modificarse.',
            ], 403);
        }

        // Verificar que el email/password correspondan a un doctor del mismo consultorio
        $doctor = \App\Models\User::where('email', $request->email)
            ->where('consultorio_id', $consultorio->id)
            ->first();

        if (!$doctor || !\Illuminate\Support\Facades\Hash::check($request->password, $doctor->password)) {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'Credenciales incorrectas.',
            ], 401);
        }

        // Devolver token temporal firmado (15 minutos) para autorizar la edición
        $token = \Illuminate\Support\Facades\Crypt::encryptString(
            json_encode([
                'cuenta_id'  => $cuenta->id,
                'doctor_id'  => $doctor->id,
                'expires_at' => now()->addMinutes(15)->timestamp,
            ])
        );

        return response()->json([
            'ok'    => true,
            'token' => $token,
        ]);
    }

    /*
|--------------------------------------------------------------------------
| ACTUALIZAR DETALLE
|--------------------------------------------------------------------------
*/
    public function actualizarDetalle(Request $request, \App\Models\DetalleCuenta $detalle)
    {
        $consultorio = Auth::user()->consultorio;

        // Verificar que la cuenta pertenece al consultorio y no está pagada
        $cuenta = CuentaPaciente::where('consultorio_id', $consultorio->id)
            ->findOrFail($detalle->cuenta_paciente_id);

        if ($cuenta->estado === 'pagado') {
            return back()->with('error', 'La factura ya fue pagada y no puede modificarse.');
        }

        // Validar token de autorización del doctor
        $this->validarTokenDoctor($request->edit_token, $cuenta->id);

        $request->validate([
            'precio'   => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:1',
        ]);

        $subtotal = $request->precio * $request->cantidad;

        $detalle->update([
            'precio'   => $request->precio,
            'cantidad' => $request->cantidad,
            'subtotal' => $subtotal,
        ]);

        // Recalcular total de la cuenta
        $nuevoTotal = $cuenta->detalles()->sum('subtotal');
        $cuenta->update(['total' => $nuevoTotal]);

        return back()->with('success', 'Detalle actualizado correctamente.');
    }

    /*
|--------------------------------------------------------------------------
| ELIMINAR DETALLE
|--------------------------------------------------------------------------
*/
    public function eliminarDetalle(Request $request, \App\Models\DetalleCuenta $detalle)
    {
        $consultorio = Auth::user()->consultorio;

        $cuenta = CuentaPaciente::where('consultorio_id', $consultorio->id)
            ->findOrFail($detalle->cuenta_paciente_id);

        if ($cuenta->estado === 'pagado') {
            return back()->with('error', 'La factura ya fue pagada y no puede modificarse.');
        }

        $this->validarTokenDoctor($request->edit_token, $cuenta->id);

        $detalle->delete();

        // Recalcular total
        $nuevoTotal = $cuenta->detalles()->sum('subtotal');
        $cuenta->update(['total' => $nuevoTotal]);

        return back()->with('success', 'Servicio eliminado de la factura.');
    }

    /*
|--------------------------------------------------------------------------
| HELPER PRIVADO — Validar token del doctor
|--------------------------------------------------------------------------
*/
    private function validarTokenDoctor(string $token = null, int $cuentaId): void
    {
        if (!$token) {
            abort(403, 'Se requiere autorización del doctor.');
        }

        try {
            $data = json_decode(
                \Illuminate\Support\Facades\Crypt::decryptString($token),
                true
            );
        } catch (\Exception $e) {
            abort(403, 'Token de autorización inválido.');
        }

        if (
            ($data['cuenta_id'] ?? null) !== $cuentaId ||
            ($data['expires_at'] ?? 0) < now()->timestamp
        ) {
            abort(403, 'Autorización expirada o inválida. Vuelva a verificar.');
        }
    }
}
