<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Models\Especialidad;
use App\Models\Consultorio;

class UserController extends Controller
{
    // LISTAR USUARIOS
    public function index()
    {
        $users = User::with('roles', 'permissions')
            ->where('consultorio_id', Auth::user()->consultorio_id)
            ->get();
        return view('usuarios.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $doctores = User::role('doctor')->get();
        $especialidades = Especialidad::all();
        $consultorios = Consultorio::all();

        return view('usuarios.create', compact('roles', 'permissions', 'doctores', 'especialidades', 'consultorios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users',
            'password' => 'required|min:6',
            'doctor_id' => 'nullable|exists:users,id',
            'consultorio_id' => 'required|exists:consultorios,id', // NUEVO
            'especialidad_id' => 'nullable|exists:especialidades,id'
        ]);

        $esDoctor = in_array('doctor', $request->roles ?? []);

        if ($esDoctor && !$request->especialidad_id) {
            return back()->withErrors([
                'especialidad_id' => 'El doctor debe tener una especialidad.'
            ]);
        }

        if ($esDoctor && !$request->consultorio_id) {
            return back()->withErrors([
                'consultorio_id' => 'El doctor debe tener un consultorio asignado.'
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'doctor_id' => $esDoctor ? null : $request->doctor_id,
            'consultorio_id' => $esDoctor ? $request->consultorio_id : Auth::user()->consultorio_id,
            'especialidad_id' => $esDoctor ? $request->especialidad_id : null,
        ]);

        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        if ($request->permissions) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    // EDITAR
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $doctores = User::role('doctor')->get();
        $especialidades = Especialidad::all();
        $consultorios = Consultorio::all();

        return view('usuarios.edit', compact('user', 'roles', 'permissions', 'doctores', 'especialidades', 'consultorios'));
    }

    public function update(Request $request, User $user)
    {
        $esDoctor = in_array('doctor', $request->roles ?? []);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'doctor_id' => $esDoctor ? null : $user->doctor_id,
            'especialidad_id' => $esDoctor ? $request->especialidad_id : null,
            'consultorio_id' => $esDoctor ? $request->consultorio_id : null,
        ]);

        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado');
    }

    public function perfil()
    {
        $user = Auth::user();
        return view('usuarios.perfil', compact('user'));
    }

    public function updatePerfil(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|unique:users,email,{$user->id}",
            'telefono' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'telefono' => $request->telefono,
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // Validar contraseña actual
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        // Guardar nueva contraseña
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

    public function toggleActivo(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes desactivarte a ti mismo.');
        }

        $user->update(['activo' => !$user->activo]);

        $estado = $user->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario {$estado} correctamente.");
    }

    public function resetPassword(User $user)
    {
        $nuevaPassword = 'Temporal123';
        $user->update(['password' => Hash::make($nuevaPassword)]);

        return back()->with('success', "Contraseña restablecida a: {$nuevaPassword}");
    }
}
