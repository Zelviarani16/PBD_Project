<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    // =============================
    //  TAMPILKAN SEMUA DATA ROLE (PAKAI VIEW)
    // =============================
    public function index()
    {
        $role = DB::select('SELECT * FROM v_role ORDER BY nama_role ASC');
        return view('role.index', compact('role'));
    }

    // =============================
    //  FORM TAMBAH ROLE
    // =============================
    public function create()
    {
        return view('role.create');
    }

    // =============================
    //  SIMPAN DATA BARU
    // =============================
    public function store(Request $request)
    {
        $request->validate([
            'idrole' => 'required|max:10',
            'nama_role' => 'required|max:100',
        ]);

        DB::table('role')->insert([
            'idrole' => $request->idrole,
            'nama_role' => $request->nama_role,
        ]);

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan!');
    }

    // =============================
    //  FORM EDIT ROLE
    // =============================
    public function edit($id)
    {
        $role = DB::select('SELECT * FROM v_role WHERE idrole = ?', [$id]);
        if (count($role) > 0) {
            $role = $role[0];
        } else {
            abort(404);
        }

        return view('role.edit', compact('role'));
    }

    // =============================
    //  UPDATE DATA ROLE
    // =============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required|max:100',
        ]);

        DB::table('role')->where('idrole', $id)->update([
            'nama_role' => $request->nama_role,
        ]);

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui!');
    }

    // =============================
    //  HAPUS DATA ROLE
    // =============================
    public function destroy($id)
    {
        DB::table('role')->where('idrole', $id)->delete();

        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus!');
    }
}
