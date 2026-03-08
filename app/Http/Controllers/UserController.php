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

        return view('usuarios.create', compact('roles', 'permissions'));
    }

    // GUARDAR USUARIO
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar roles
        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        // Asignar permisos individuales
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

    // ACTUALIZAR
    public function update(Request $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Sincroniza roles
        $user->syncRoles($request->roles ?? []);

        // Sincroniza permisos individuales
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado');
    }

    // Mostrar perfil del usuario actual
    public function perfil()
    {
        $user = Auth::user();
        return view('usuarios.perfil', compact('user'));
    }

    // Actualizar información básica
    public function updatePerfil(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    // Cambiar contraseña
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
}
