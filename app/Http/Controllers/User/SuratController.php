<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SuratPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    // Form upload surat
    public function create()
    {
        return view('borrowing.upload-surat');
    }

    // Simpan surat yang diupload
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',
            'keperluan' => 'nullable|string|max:500',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'surat' => 'required|file|mimes:pdf,doc,docx|max:5120' // PDF atau Word, max 5MB
        ], [
            'no_hp.regex' => 'Format nomor HP tidak valid',
            'surat.mimes' => 'File harus berupa PDF atau Word (doc, docx)',
            'surat.max' => 'Ukuran file maksimal 5MB'
        ]);

        try {
            // Upload file
            if ($request->hasFile('surat')) {
                $file = $request->file('surat');
                $user = Auth::user();

                // Generate nama file yang aman
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
                $fileName = 'surat_' . $user->id . '_' . time() . '_' . $safeName . '.' . $extension;

                // Simpan file ke storage
                $path = $file->storeAs('surat_peminjaman', $fileName, 'public');

                // Simpan ke database
                SuratPeminjaman::create([
                    'user_id' => $user->id,
                    'nama_pemohon' => $request->nama_pemohon,
                    'no_hp' => $request->no_hp,
                    'keperluan' => $request->keperluan,
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'surat_path' => $path,
                    'status' => 'pending'
                ]);

                return redirect()->route('borrowing.dashboard')
                    ->with('success', '✅ Surat berhasil diupload! Admin akan memverifikasi dalam 1-2 hari kerja.');
            }

            return back()->with('error', '❌ Gagal mengupload file');

        } catch (\Exception $e) {
            return back()->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $templatePath = storage_path('app/public/templates/template_surat_pinjam_new.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'File template tidak ditemukan. Hubungi admin.');
        }

        $timestamp = time();
        $fileName = "Template_Surat_Peminjaman_{$timestamp}.docx";

        return response()->download(
            $templatePath,
            $fileName,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]
        );
    }

    // Template default buat jaga-jaga
    private function createDefaultTemplate()
    {
        $templateDir = storage_path('app/public/templates');

        // Buat folder jika belum ada
        if (!is_dir($templateDir)) {
            mkdir($templateDir, 0755, true);
        }

        $templatePath = $templateDir . '/template_surat_pinjaman.docx';

        // Isi template sederhana dalam format text
        $templateContent = "SURAT PERMOHONAN PEMINJAMAN ALAT LABORATORIUM PTIK\n\n";

        // Simpan sebagai .txt dulu (bisa diganti dengan .docx nanti)
        file_put_contents($templateDir . '/template_surat_pinjaman.txt', $templateContent);

        // Copy ke .docx (format sederhana)
        copy($templateDir . '/template_surat_pinjaman.txt', $templatePath);

        return file_exists($templatePath);
    }

    // Menampilkan daftar surat jiakhh
    public function index()
    {
        $user = Auth::user();
        $suratList = SuratPeminjaman::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('borrowing.daftar-surat', compact('suratList'));
    }

    // Cancel surat
    public function cancel($id)
    {
        $user = Auth::user();
        $surat = SuratPeminjaman::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        // Hapus file dari storage
        if ($surat->surat_path && Storage::disk('public')->exists($surat->surat_path)) {
            Storage::disk('public')->delete($surat->surat_path);
        }

        // Hapus dari database
        $surat->delete();

        return redirect()->route('borrowing.surat.list')
            ->with('success', 'Surat berhasil dibatalkan');
    }
}