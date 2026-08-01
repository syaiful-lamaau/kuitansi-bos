<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Receipt;
use App\Helpers\TerbilangHelper;

class ReceiptController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate(['id' => 1], [
            'tahun_anggaran' => date('Y'),
            'nama_madrasah' => 'Madrasah',
            'alamat' => '',
            'sumber_dana' => '',
            'total_pagu_anggaran' => 0,
            'nama_kepala' => '',
            'nip_kepala' => '',
            'nama_bendahara' => '',
            'nip_bendahara' => ''
        ]);

        $receiptsQuery = Receipt::orderBy('id', 'desc')->take(5);
        if (request()->has('edit_id')) {
            $receiptsQuery->orWhere('id', request('edit_id'));
        }
        $receipts = $receiptsQuery->get();

        $lastReceipt = Receipt::orderBy('id', 'desc')->first();
        $nextId = 1;
        if ($lastReceipt && preg_match('/^(\d+)\//', $lastReceipt->nomor_bukti, $matches)) {
            $nextId = intval($matches[1]) + 1;
        }
        $tahun = $setting->tahun_anggaran ?? date('Y');
        $next_nomor_bukti = str_pad($nextId, 3, '0', STR_PAD_LEFT) . '/BOS/' . $tahun;
        $total_pengeluaran = Receipt::sum('jumlah');

        return view('receipts.index', compact('setting', 'receipts', 'next_nomor_bukti', 'total_pengeluaran'));
    }

    public function storeSetting(Request $request)
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        
        // Prevent changing year if there are receipts
        if ($setting->tahun_anggaran && $request->tahun_anggaran != $setting->tahun_anggaran) {
            if (Receipt::count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa mengganti Tahun Anggaran karena masih ada kuitansi yang tersimpan. Silakan Hapus Semua kuitansi terlebih dahulu.'
                ], 422);
            }
        }

        $data = $request->all();
        if (empty($data['nip_kepala'])) {
            $data['nip_kepala'] = ' -';
        }
        if (empty($data['nip_bendahara'])) {
            $data['nip_bendahara'] = ' -';
        }

        $setting->update($data);

        return response()->json(['success' => true]);
    }

    public function list(Request $request)
    {
        $query = Receipt::orderBy('id', 'desc');
        $search = $request->search;

        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nama_penerima', 'like', "%{$search}%")
                  ->orWhere('nomor_bukti', 'like', "%{$search}%")
                  ->orWhere('tanggal', 'like', "%{$search}%")
                  ->orWhere('jumlah', 'like', "%{$search}%")
                  ->orWhere('untuk_pembayaran', 'like', "%{$search}%");
            });
        }

        // Limit to 25 per page as requested by user
        $receipts = $query->paginate(25)->withQueryString();

        return view('receipts.list', compact('receipts', 'search'));
    }

    public function storeReceipt(Request $request)
    {
        $request->validate([
            'sudah_terima_dari' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'untuk_pembayaran' => 'required',
            'tanggal' => 'required|date'
        ]);

        $setting = Setting::first();

        if (!$setting || empty($setting->nama_madrasah) || empty($setting->tahun_anggaran) || !isset($setting->total_pagu_anggaran) || empty($setting->alamat) || empty($setting->sumber_dana) || empty($setting->nama_kepala) || empty($setting->nama_bendahara)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan isi dan simpan SEMUA data pada tab Pengaturan terlebih dahulu sebelum membuat kuitansi.'
            ], 422);
        }
        
        $tahun = $setting->tahun_anggaran ?? date('Y');
        
        if ($request->filled('nomor_bukti')) {
            $nomor_bukti = $request->nomor_bukti;
        } else {
            $lastReceipt = Receipt::orderBy('id', 'desc')->first();
            $nextId = 1;
            if ($lastReceipt && preg_match('/^(\d+)\//', $lastReceipt->nomor_bukti, $matches)) {
                $nextId = intval($matches[1]) + 1;
            }
            $nomor_bukti = str_pad($nextId, 3, '0', STR_PAD_LEFT) . '/BOS/' . $tahun;
        }

        if (Receipt::where('nomor_bukti', $nomor_bukti)->exists()) {
            return response()->json(['message' => 'Kuitansi dengan Nomor Bukti ' . $nomor_bukti . ' sudah ada di database! Silakan hapus data kuitansi lama atau ubah tahun anggaran.'], 422);
        }

        $receipt = new Receipt($request->except(['_token', 'nomor_bukti']));
        $receipt->nomor_bukti = $nomor_bukti;
        $receipt->terbilang = TerbilangHelper::format($request->jumlah);
        
        if (!$receipt->sumber_dana && $setting) {
            $receipt->sumber_dana = $setting->sumber_dana;
        }

        $receipt->save();

        $newLastReceipt = Receipt::orderBy('id', 'desc')->first();
        $newNextId = 1;
        if ($newLastReceipt && preg_match('/^(\d+)\//', $newLastReceipt->nomor_bukti, $matches)) {
            $newNextId = intval($matches[1]) + 1;
        }
        $next_nomor_bukti_baru = str_pad($newNextId, 3, '0', STR_PAD_LEFT) . '/BOS/' . $tahun;

        return response()->json([
            'success' => true, 
            'receipt' => $receipt,
            'print_url' => url('/receipts/' . $receipt->id . '/print'),
            'next_nomor_bukti' => $next_nomor_bukti_baru
        ]);
    }

    public function update(Request $request, $id)
    {
        $receipt = Receipt::findOrFail($id);
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'untuk_pembayaran' => 'required',
            'tanggal' => 'required|date'
        ]);

        $receipt->update($request->only(['nama_penerima', 'tanggal', 'jumlah', 'untuk_pembayaran']));
        $receipt->terbilang = TerbilangHelper::format($receipt->jumlah);
        $receipt->save();

        return response()->json([
            'success' => true, 
            'receipt' => $receipt,
            'print_url' => url('/receipts/' . $receipt->id . '/print')
        ]);
    }

    public function print($id)
    {
        $receipt = Receipt::findOrFail($id);
        $setting = Setting::first();
        return view('receipts.print', compact('receipt', 'setting'));
    }
    
    public function printAll()
    {
        $setting = Setting::first();
        // Fetch all receipts ordered by ID ASC or DESC? Usually ASC for printing sequentially
        $receipts = Receipt::orderBy('id', 'asc')->get();

        if ($receipts->isEmpty()) {
            return back()->with('error', 'Tidak ada kuitansi untuk dicetak.');
        }

        return view('receipts.print_all', compact('receipts', 'setting'));
    }

    public function destroy($id)
    {
        $receipt = Receipt::findOrFail($id);
        $receipt->delete();

        return response()->json(['success' => true]);
    }

    public function destroyAll()
    {
        Receipt::truncate();
        
        // Reset pagu anggaran to 0 to prevent user from forgetting to update it for the new year
        $setting = Setting::first();
        if ($setting) {
            $setting->total_pagu_anggaran = 0;
            $setting->save();
        }

        return response()->json(['success' => true]);
    }
}
