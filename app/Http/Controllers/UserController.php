<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // TAMPILKAN SEMUA USER (PAKAI VIEW v_user)
    public function index()
    {
        // Ambil semua data user dari view v_user
        $users = DB::select('SELECT * FROM v_user ORDER BY username ASC');

        // Kirim data ke view
        return view('user.index', compact('users'));
    }

    // FORM TAMBAH USER
    public function create()
    {
        // Ambil semua role dari tabel role (atau bisa view kalau kamu punya)
        $role = DB::select('SELECT * FROM v_role ORDER BY nama_role ASC');
        return view('user.create', compact('role'));
    }

    // SIMPAN USER BARU
    public function store(Request $request)
    {
        $request->validate([
            'iduser' => 'required|max:10',
            'username' => 'required|max:45',
            'password' => 'required|min:6',
            'idrole' => 'required',
        ]);

        $iduser = $request->iduser;
        $username = $request->username;
        $password = bcrypt($request->password);
        $idrole = $request->idrole;

        // Query mentah untuk insert ke tabel user
        DB::statement('INSERT INTO user (iduser, username, password, idrole) VALUES (?, ?, ?, ?)', [
            $iduser, $username, $password, $idrole
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    // FORM EDIT USER
    public function edit($id)
    {
        $user = DB::table('user')
            ->join('role', 'user.idrole', '=', 'role.idrole')
            ->select('user.*', 'role.nama_role', 'role.idrole')
            ->where('user.iduser', $id)
            ->first(); // langsung object

        if (!$user) abort(404);

        $role = DB::table('role')->orderBy('nama_role', 'asc')->get();

        return view('user.edit', compact('user', 'role'));
    }


    // UPDATE USER
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|max:45',
            'password' => 'nullable|min:6',
            'idrole' => 'required',
        ]);

        $username = $request->username;
        $idrole = $request->idrole;

        if ($request->filled('password')) {
            $password = bcrypt($request->password);
            DB::statement('UPDATE user SET username = ?, password = ?, idrole = ? WHERE iduser = ?', [
                $username, $password, $idrole, $id
            ]);
        } else {
            DB::statement('UPDATE user SET username = ?, idrole = ? WHERE iduser = ?', [
                $username, $idrole, $id
            ]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    // HAPUS USER
    public function destroy($id)
    {
        try {
            DB::statement('DELETE FROM user WHERE iduser = ?', [$id]);
            return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('error', 'Gagal menghapus user. Data mungkin sedang digunakan.');
        }
    }

}
