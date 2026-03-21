<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    // LISTAR USUARIOS
    public function index()
    {
        $users = User::with('roles', 'permissions')->get();
        return view('usuarios.index', compact('users'));
    }

    // FORM CREAR
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $doctores = User::role('doctor')->get();

        return view('usuarios.create', compact('roles', 'permissions', 'doctores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users',
            'password' => 'required|min:6',
            'doctor_id' => 'nullable|exists:users,id'
        ]);

        $esDoctor = in_array('doctor', $request->roles ?? []);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'doctor_id' => $esDoctor ? null : $request->doctor_id,
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

        return view('usuarios.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $esDoctor = in_array('doctor', $request->roles ?? []);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'doctor_id' => $esDoctor ? null : $user->doctor_id,
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
