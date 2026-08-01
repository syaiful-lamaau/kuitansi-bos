@extends('layouts.app')

@section('content')
    <!-- Tab Navigation -->
    <div class="flex border-b border-gray-700 mb-6">
        <a href="{{ route('home') }}?tab=setting"
            class="pl-px pr-3 py-3 font-semibold text-gray-400 hover:text-blue-400 focus:outline-none">Pengaturan (Sekali
            Isi)</a>
        <a href="{{ route('home') }}"
            class="px-6 py-3 font-semibold text-gray-400 hover:text-blue-400 focus:outline-none">Buat Kuitansi</a>
        <span class="px-6 py-3 font-semibold border-b-2 border-blue-400 text-blue-400 focus:outline-none">Daftar
            Kuitansi</span>
    </div>

    <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700" x-data="receiptList()">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Daftar Seluruh Kuitansi</h2>

            <!-- Search Form -->
            <form action="{{ route('receipts.list') }}" method="GET" class="flex space-x-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari kuitansi..."
                    class="border border-gray-600 bg-gray-700 text-gray-100 p-2 rounded focus:border-blue-500 focus:ring focus:ring-blue-200">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Cari</button>
                @if(request('search'))
                    <a href="{{ route('receipts.list') }}"
                        class="bg-gray-400 text-white px-4 py-2 rounded shadow hover:bg-gray-500 flex items-center">Reset</a>
                @endif
                <a href="{{ route('receipts.print_all') }}" target="_blank"
                    class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 flex items-center ml-2">Cetak
                    Semua</a>
                <button type="button" @click="deleteAllReceipts" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700 flex items-center ml-2">Hapus Semua</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-400">Nomor Bukti</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-400">Tanggal</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-400">Penerima Uang</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-400">Jumlah</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-400">Untuk Pembayaran</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($receipts as $item)
                        <tr class="hover:bg-gray-700">
                            <td class="px-4 py-3 text-sm font-mono text-gray-300">{{ $item->nomor_bukti }}</td>
                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->nama_penerima ?: 'ditulis di tempat' }}</td>
                            <td class="px-4 py-3 text-sm font-medium">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-400 truncate max-w-xs">{{ $item->untuk_pembayaran }}</td>
                            <td class="px-4 py-3 text-sm text-center flex justify-center items-center space-x-3">
                                <a href="{{ url('/receipts/' . $item->id . '/print') }}" target="_blank"
                                    class="text-blue-400">Cetak</a>
                                <a href="{{ route('home', ['edit_id' => $item->id]) }}" class="text-orange-400">Edit</a>
                                <button @click="deleteReceipt({{ $item->id }})" class="text-red-400">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400">Tidak ada data kuitansi ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $receipts->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('receiptList', () => ({
                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                },
                async deleteReceipt(id) {
                    Swal.fire({
                        title: 'Hapus Kuitansi?',
                        text: 'Yakin ingin menghapus kuitansi ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                await axios.delete('/receipts/' + id);
                                window.location.reload();
                            } catch (error) {
                                Swal.fire('Gagal!', 'Gagal menghapus kuitansi', 'error');
                            }
                        }
                    });
                },
                async deleteAllReceipts() {
                    Swal.fire({
                        title: 'Hapus SEMUA Kuitansi?',
                        text: 'Anda yakin ingin menghapus SELURUH kuitansi? Pastikan Anda sudah mengunduh/mencetak semua kuitansi terlebih dahulu!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus Semua!',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                await axios.delete('/receipts/delete-all');
                                window.location.reload();
                            } catch (error) {
                                Swal.fire('Gagal!', 'Gagal menghapus semua kuitansi', 'error');
                            }
                        }
                    });
                }
            }));
        });
    </script>
@endsection