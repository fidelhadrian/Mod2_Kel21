<?php

// Dalam laravel, namespace digunakan untuk mengorganisasi dan mengelompokkan function atau class yang berkaitan
// Kode dibawah berfungsi untuk mendefinisikan namespace pada controller AdminController.
namespace App\Http\Controllers;

// Kode use dalam php digunakan untuk mengimpor data, class, atau namespace ke suatu file
// Kode dibawah berfungsi mengimpor file Controller, Request, DB.
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Membuat class AdminController yang mewarisi (inharitance) dari Controller
class AdminController extends Controller
{
    // Function create() berfungsi untuk menampilkan tampilan tambah data atau add untuk membuat data admin baru.
    public function create()
    {
        return view('admin.add');
    }
    
    // Function store berfungsi untuk menyimpan data ke dalam tabel.
    public function store(Request $request)
    {
        // Kemudian menjalankan perintah untuk melakukan validasi data yang diterima dari form agar harus semua terisi.
        $request->validate([
            'id_admin' => 'required',
            'nama_admin' => 'required',
            'alamat' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);
        
        // Dilanjutkan memasukkan data-data baru admin ke dalam tabel "admin" menggunakan SQL INSERT.
        DB::insert(
            'INSERT INTO admin(id_admin,nama_admin, alamat, username, password) VALUES (:id_admin, :nama_admin, :alamat, :username, :password)',
            [
                'id_admin' => $request->id_admin,
                'nama_admin' => $request->nama_admin,
                'alamat' => $request->alamat,
                'username' => $request->username,
                'password' => $request->password,
            ]
        );
        
        // Setelah data berhasil dimasukan, mengembalikan pengguna ke halaman indeks admin dengan pesan 'Data Admin berhasil disimpan'.
        return redirect()->route('admin.index')->with('success', 'Data Admin berhasil disimpan');
    }

    // Function index() berfungsi untuk menampilkan semua data dari tabel "admin".
    public function index()
    {
        // Perintah untuk mengambil semua data admin dari tabel menggunakan SQL SELECT.
        $datas = DB::select('select * from admin');
        
        // Perintah untuk menampilkan data ke dalam tampilan "admin.index".
        return view('admin.index')->with('datas', $datas);
    }

    // Function edit berfungsi untuk mengedit baris data dalam tabel "admin".
    public function edit($id)
    {
        // Perintah untuk mengambil data admin berdasarkan ID yang diberikan dari tabel "admin" menggunakan syntax SQL.
        $data = DB::table('admin')->where('id_admin', $id)->first();
        
        // Perintah untuk menampilkan data ke dalam tampilan "admin.edit" untuk diedit.
        return view('admin.edit')->with('data', $data);
    }

    // Function update untuk mengupdate data dalam tabel "admin".
    public function update($id, Request $request)
    {
        // Pertama dengan melakukan validasi data yang diterima dari formulir.
        $request->validate([
            'id_admin' => 'required',
            'nama_admin' => 'required',
            'alamat' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        // Kemudian melakukan pembaruan data admin dalam tabel "admin" berdasarkan ID yang diberikan menggunakan perintah SQL UPDATE.
        DB::update(
            'UPDATE admin SET id_admin = :id_admin, nama_admin = :nama_admin, alamat = :alamat, username = :username, password = :password WHERE id_admin = :id',
            [
                'id' => $id,
                'id_admin' => $request->id_admin,
                'nama_admin' => $request->nama_admin,
                'alamat' => $request->alamat,
                'username' => $request->username,
                'password' => $request->password,
            ]
        );

        // Perintah untuk mengarahkan pengguna kembali ke halaman indeks admin dengan pesan.
        // Perintah ini terhubung dengan route dari file web.php
        return redirect()->route('admin.index')->with('success', 'Data Admin berhasil diubah');
    }

    // Function delete() untuk menghapus baris data dari tabel "admin" dengan variabel id sebagai referensi.
    public function delete($id)
    {
        // Perintah untuk menghapus data admin berdasarkan ID yang diberikan dari tabel "admin" menggunakan SQL DELETE.
        DB::delete('DELETE FROM admin WHERE id_admin = :id_admin', ['id_admin' => $id]);
        
        // Perintah untuk mengarahkan pengguna kembali ke halaman indeks admin dengan pesan.
        // Perintah ini terhubung dengan route dari file web.php
        return redirect()->route('admin.index')->with('success', 'Data Admin berhasil dihapus');
    }

    // Function search() berfungsi untuk melakukan pencarian data berdasarkan data dalam tabel 'admin' dengan mencocokan nilai 'nama_admin' yang sesuai
    public function search(Request $request)
    {
        $query = $request->input('query');
        $datas = DB::table('admin')
                    ->where('nama_admin', 'LIKE', "%$query%")
                    ->get();
        return view('admin.index', compact('datas'));
    }
}
