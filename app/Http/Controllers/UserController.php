<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    // LISTAR USUARIOS
    public function index()
    {
        $users = User::with('roles','permissions')->get();
        return view('usuarios.index', compact('users'));
    }

    // FORM CREAR
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('usuarios.create', compact('roles','permissions'));
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
        if($request->roles){
            $user->syncRoles($request->roles);
        }

        // Asignar permisos individuales
        if($request->permissions){
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('usuarios.index')
                ->with('success','Usuario creado correctamente');
    }

    // EDITAR
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('usuarios.edit', compact('user','roles','permissions'));
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
                ->with('success','Usuario actualizado');
    }
}
