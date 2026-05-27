<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caja;
use App\Models\MovimientoCaja;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ABRIR CAJA
    |--------------------------------------------------------------------------
    */

    public function abrir()
    {
        $consultorio = Auth::user()->consultorio;

        // Si el consultorio no usa caja
        if (!$consultorio->usar_caja) {

            return redirect()
                ->route('pacientes.inicio')
                ->with('error', 'Este consultorio no utiliza módulo de caja.');
        }

        $caja = Caja::where('consultorio_id', $consultorio->id)
            ->where('estado', 'abierta')
            ->first();

        if ($caja) {

            return redirect()
                ->route('caja.panel')
                ->with('error', 'Ya existe una caja abierta.');
        }

        return view('caja.abrir');
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR APERTURA
    |--------------------------------------------------------------------------
    */

    public function guardarApertura(Request $request)
    {
        $consultorio = Auth::user()->consultorio;

        if (!$consultorio->usar_caja) {

            return back()->with(
                'error',
                'Este consultorio no utiliza caja.'
            );
        }

        $request->validate([
            'monto_inicial' => 'required|numeric|min:0'
        ]);

        $cajaExistente = Caja::where('consultorio_id', $consultorio->id)
            ->where('estado', 'abierta')
            ->first();

        if ($cajaExistente) {

            return back()->with(
                'error',
                'Ya existe una caja abierta.'
            );
        }

        Caja::create([
            'consultorio_id' => $consultorio->id,
            'usuario_id' => Auth::id(),
            'monto_inicial' => $request->monto_inicial,
            'monto_final' => 0,
            'fecha_apertura' => now(),
            'estado' => 'abierta'
        ]);

        return redirect()
            ->route('caja.panel')
            ->with('success', 'Caja abierta correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | PANEL
    |--------------------------------------------------------------------------
    */

    public function panel()
    {
        $consultorio = Auth::user()->consultorio;

        if (!$consultorio->usar_caja) {

            return redirect()
                ->route('pacientes.inicio')
                ->with('error', 'Este consultorio no utiliza caja.');
        }

        $caja = Caja::where('consultorio_id', $consultorio->id)
            ->where('estado', 'abierta')
            ->first();

        if (!$caja) {

            return redirect()
                ->route('caja.abrir')
                ->with('error', 'No hay una caja abierta.');
        }

        $movimientos = $caja->movimientos()
            ->latest()
            ->get();

        $ingresos = $movimientos
            ->where('tipo', 'ingreso')
            ->sum('monto');

        $egresos = $movimientos
            ->where('tipo', 'egreso')
            ->sum('monto');

        $saldo = $caja->monto_inicial + $ingresos - $egresos;

        return view('caja.panel', compact(
            'caja',
            'movimientos',
            'ingresos',
            'egresos',
            'saldo'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTRAR PAGO
    |--------------------------------------------------------------------------
    */

    public function registrarPago(Request $request)
    {
        $consultorio = Auth::user()->consultorio;

        $request->validate([
            'monto' => 'required|numeric|min:1',
            'concepto' => 'required|string|max:255',
            'paciente_id' => 'nullable',
            'metodo_pago' => 'nullable|string|max:50'
        ]);

        // Si NO usa caja, simplemente continuar
        if (!$consultorio->usar_caja) {

            return back()->with(
                'success',
                'Pago registrado correctamente.'
            );
        }

        $caja = Caja::where('consultorio_id', $consultorio->id)
            ->where('estado', 'abierta')
            ->first();

        if (!$caja) {

            return back()->with(
                'error',
                'No hay una caja abierta.'
            );
        }

        MovimientoCaja::create([
            'consultorio_id' => $consultorio->id,
            'caja_id' => $caja->id,
            'tipo' => 'ingreso',
            'concepto' => $request->concepto,
            'monto' => $request->monto,
            'paciente_id' => $request->paciente_id,
            'metodo_pago' => $request->metodo_pago,
            'usuario_id' => Auth::id()
        ]);

        return back()->with(
            'success',
            'Pago registrado correctamente.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTRAR GASTO
    |--------------------------------------------------------------------------
    */

    public function registrarGasto(Request $request)
    {
        $consultorio = Auth::user()->consultorio;

        if (!$consultorio->usar_caja) {

            return back()->with(
                'error',
                'Este consultorio no utiliza caja.'
            );
        }

        $request->validate([
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:1'
        ]);

        $caja = Caja::where('consultorio_id', $consultorio->id)
            ->where('estado', 'abierta')
            ->first();

        if (!$caja) {

            return back()->with(
                'error',
                'No hay una caja abierta.'
            );
        }

        MovimientoCaja::create([
            'consultorio_id' => $consultorio->id,
            'caja_id' => $caja->id,
            'tipo' => 'egreso',
            'concepto' => $request->concepto,
            'monto' => $request->monto,
            'usuario_id' => Auth::id()
        ]);

        return back()->with(
            'success',
            'Gasto registrado.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CERRAR CAJA
    |--------------------------------------------------------------------------
    */

    public function cerrar(Request $request)
    {
        $consultorio = Auth::user()->consultorio;

        if (!$consultorio->usar_caja) {

            return back()->with(
                'error',
                'Este consultorio no utiliza caja.'
            );
        }

        $request->validate([
            'monto_final' => 'required|numeric|min:0'
        ]);

        $caja = Caja::where('consultorio_id', $consultorio->id)
            ->where('estado', 'abierta')
            ->first();

        if (!$caja) {

            return back()->with(
                'error',
                'No hay una caja abierta.'
            );
        }

        $movimientos = $caja->movimientos;

        $ingresos = $movimientos
            ->where('tipo', 'ingreso')
            ->sum('monto');

        $egresos = $movimientos
            ->where('tipo', 'egreso')
            ->sum('monto');

        $saldoSistema = $caja->monto_inicial + $ingresos - $egresos;

        $caja->update([
            'monto_final' => $request->monto_final,
            'fecha_cierre' => now(),
            'estado' => 'cerrada'
        ]);

        return redirect()
            ->route('caja.reporte', $caja->id)
            ->with(
                'success',
                'Caja cerrada correctamente.'
            );
    }
}
