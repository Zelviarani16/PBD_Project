<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    // 🔹 TAMPILKAN SEMUA ROLE (pakai view v_role)
    public function index()
    {
        // Ambil semua data role dari view v_role
        $role = DB::select('SELECT * FROM v_role ORDER BY nama_role ASC');

        // Kirim data ke view
        return view('role.index', compact('role'));
    }

    // 🔹 FORM TAMBAH ROLE
    public function create()
    {
        return view('role.create');
    }

    // 🔹 SIMPAN ROLE BARU (pakai stored procedure)
    public function store(Request $request)
    {
        $request->validate([
            'idrole' => 'required|max:10',
            'nama_role' => 'required|max:45',
        ]);

        $idrole = $request->idrole;
        $nama_role = $request->nama_role;

        // Panggil stored procedure untuk tambah role
        DB::statement('CALL sp_tambah_role(?, ?)', [
            $idrole,
            $nama_role
        ]);

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan!');
    }

    // 🔹 FORM EDIT ROLE
    public function edit($id)
    {
        // Ambil data role berdasarkan id dari view
        $role = DB::select('SELECT * FROM v_role WHERE idrole = ?', [$id]);

        if (count($role) > 0) {
            $role = $role[0];
        } else {
            abort(404);
        }

        return view('role.edit', compact('role'));
    }

    // 🔹 UPDATE ROLE (pakai stored procedure)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required|max:45',
        ]);

        $nama_role = $request->nama_role;

        // Panggil stored procedure untuk update role
        DB::statement('CALL sp_update_role(?, ?)', [
            $id,
            $nama_role
        ]);

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui!');
    }

    // 🔹 HAPUS ROLE (pakai stored procedure)
    public function destroy($id)
    {
        try {
            DB::statement('CALL sp_hapus_role(?)', [$id]);
            return redirect()->route('role.index')->with('success', 'Role berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('role.index')->with('error', 'Gagal menghapus role. Data mungkin sedang digunakan.');
        }
    }
}
