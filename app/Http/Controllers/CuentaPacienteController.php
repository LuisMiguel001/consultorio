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
}
