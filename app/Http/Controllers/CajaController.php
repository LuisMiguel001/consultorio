<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caja;
use Illuminate\Support\Facades\Auth;
use App\Models\MovimientoCaja;

class CajaController extends Controller
{
    public function abrir()
    {
        $cajaAbierta = Caja::where('estado', 'abierta')->first();

        if ($cajaAbierta) {
            return redirect()->back()->with('error', 'Ya existe una caja abierta');
        }

        return view('caja.abrir');
    }

    public function guardarApertura(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric'
        ]);

        Caja::create([
            'usuario_id' => Auth::id(),
            'monto_inicial' => $request->monto_inicial,
            'estado' => 'abierta'
        ]);

        return redirect()->route('caja.panel');
    }

    public function panel()
    {
        $caja = Caja::where('estado', 'abierta')->first();

        if (!$caja) {
            return redirect()->route('caja.abrir');
        }

        $movimientos = $caja->movimientos()->latest()->get();

        $ingresos = $movimientos->where('tipo', 'ingreso')->sum('monto');

        $egresos = $movimientos->where('tipo', 'egreso')->sum('monto');

        $saldo = $caja->monto_inicial + $ingresos - $egresos;

        return view('caja.panel', compact(
            'caja',
            'movimientos',
            'ingresos',
            'egresos',
            'saldo'
        ));
    }

    public function registrarPago(Request $request)
    {
        $request->validate([
            'monto' => 'required|numeric',
            'concepto' => 'required'
        ]);

        $caja = Caja::where('estado', 'abierta')->first();

        MovimientoCaja::create([
            'caja_id' => $caja->id,
            'tipo' => 'ingreso',
            'concepto' => $request->concepto,
            'monto' => $request->monto,
            'paciente_id' => $request->paciente_id,
            'metodo_pago' => $request->metodo_pago,
            'usuario_id' => Auth::id()
        ]);

        return back()->with('success', 'Pago registrado');
    }

    public function registrarGasto(Request $request)
    {
        $request->validate([
            'concepto' => 'required',
            'monto' => 'required|numeric'
        ]);

        $caja = Caja::where('estado', 'abierta')->first();

        MovimientoCaja::create([
            'caja_id' => $caja->id,
            'tipo' => 'egreso',
            'concepto' => $request->concepto,
            'monto' => $request->monto,
            'usuario_id' => Auth::id()
        ]);

        return back();
    }

    public function cerrar(Request $request)
    {
        $caja = Caja::where('estado', 'abierta')->first();

        $movimientos = $caja->movimientos;

        $ingresos = $movimientos->where('tipo', 'ingreso')->sum('monto');
        $egresos = $movimientos->where('tipo', 'egreso')->sum('monto');

        $saldo = $caja->monto_inicial + $ingresos - $egresos;

        $caja->update([
            'monto_final' => $request->monto_final,
            'fecha_cierre' => now(),
            'estado' => 'cerrada'
        ]);

        return redirect()->route('caja.reporte', $caja->id);
    }
}
