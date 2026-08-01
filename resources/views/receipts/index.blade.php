@extends('layouts.app')

@section('content')
    <div x-data="kuitansiApp()" x-init="init()" x-cloak>

        <!-- Tab Navigation -->
        <div class="flex border-b border-gray-700 mb-6">
            <a href="#" @click.prevent="tab = 'setting'; resetFormState()"
                :class="tab === 'setting' ? 'border-b-2 border-blue-400 text-blue-400' : 'text-gray-400'"
                class="px-6 py-3 font-semibold hover:text-blue-400 focus:outline-none">Pengaturan (Sekali Isi)</a>
            <a href="#" @click.prevent="tab = 'kuitansi'"
                :class="tab === 'kuitansi' ? 'border-b-2 border-blue-400 text-blue-400' : 'text-gray-400'"
                class="px-6 py-3 font-semibold hover:text-blue-400 focus:outline-none">Buat Kuitansi</a>
            <a href="{{ route('receipts.list') }}"
                class="px-6 py-3 font-semibold text-gray-400 hover:text-blue-400 focus:outline-none">Daftar Kuitansi</a>
        </div>

        <!-- Tab Setting -->
        <div x-show="tab === 'setting'">
            <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700 mb-6">
                <h2 class="text-xl font-bold mb-4 text-white">Pengaturan Global</h2>
                <form @submit.prevent="saveSetting">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Tahun Anggaran</label>
                            <input type="text" x-model="setting.tahun_anggaran" :disabled="!isSettingEditing"
                                class="w-full border border-gray-600 bg-gray-700 text-gray-100 p-2 rounded disabled:bg-gray-800 disabled:text-gray-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Nama Madrasah</label>
                            <input type="text" x-model="setting.nama_madrasah" :disabled="!isSettingEditing"
                                class="w-full border border-gray-600 bg-gray-700 text-gray-100 p-2 rounded disabled:bg-gray-800 disabled:text-gray-500"
                                required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm mb-1">Alamat</label>
                            <input type="text" x-model="setting.alamat" :disabled="!isSettingEditing"
                                class="w-full border border-gray-600 bg-gray-700 text-gray-100 p-2 rounded disabled:bg-gray-800 disabled:text-gray-500">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Sumber Dana</label>
                            <input type="text" x-model="setting.sumber_dana" :disabled="!isSettingEditing"
                                class="w-full border border-gray-600 bg-gray-700 text-gray-100 p-2 rounded disabled:bg-gray-800 disabled:text-gray-500">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Total Pagu Anggaran (Rp)</label>
                            <input type="text" x-model="pagu_format" @input="formatInputPagu" :disabled="!isSettingEditing"
                                class="w-full border border-gray-600 bg-gray-700 text-gray-100 p-2 rounded disabled:bg-gray-800 disabled:text-gray-500"
                                placeholder="Contoh: 10000000">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Kepala Madrasah</label>
                            <input type="text" x-model="setting.nama_kepala" :disabled="!isSettingEditing"
                                class="w-full border border-gray-600 bg-gray-700 text-gray-100 p-2 rounded disabled:bg-gray-800 disabled:text-gray-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">NIP Kepala Madrasah</label>
                            <input type="text" x-model="setting.nip_kepala" :disabled="!isSettingEditing"
                                class="w-full border border-gray-600 bg-gray-700 text-gray-100 p-2 rounded disabled:bg-gray-800 disabled:text-gray-500">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Bendahara Madrasah</label>
                            <input type="text" x-model="setting.nama_bendahara" :disabled="!isSettingEditing"
                                class="w-full border border-gray-600 bg-gray-700 text-gray-100 p-2 rounded disabled:bg-gray-800 disabled:text-gray-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">NIP Bendahara</label>
                            <input type="text" x-model="setting.nip_bendahara" :disabled="!isSettingEditing"
                                class="w-full border border-gray-600 bg-gray-700 text-gray-100 p-2 rounded disabled:bg-gray-800 disabled:text-gray-500">
                        </div>
                    </div>
                    <div class="mt-6 flex space-x-3">
                        <button type="button" x-show="!isSettingEditing" @click="editSetting"
                            class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">
                            Edit Pengaturan
                        </button>
                        <button type="submit" x-show="isSettingEditing"
                            class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 disabled:opacity-50"
                            :disabled="loading">
                            <span x-text="loading ? 'Menyimpan...' : 'Simpan Pengaturan'"></span>
                        </button>
                        <button type="button" x-show="isSettingEditing" @click="cancelSettingEdit"
                            class="bg-gray-400 text-white px-6 py-2 rounded shadow hover:bg-gray-500 transition-colors">
                            Batal
                        </button>
                        <span x-show="message" x-text="message" class="ml-4 text-green-600 font-medium"></span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab Kuitansi -->
        <div x-show="tab === 'kuitansi'">
            <div class="grid grid-cols-1 gap-6">
                <!-- Form -->
                <div>
                    <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700">
                        <div class="mb-4 pb-4 border-b border-gray-700">
                            <h2 class="text-xl font-bold text-white">Buat Kuitansi</h2>
                            <p class="text-sm text-gray-400">Pastikan Pengaturan telah diisi dan disimpan.</p>
                            <div
                                class="bg-blue-900 text-blue-100 p-3 rounded text-sm font-semibold flex justify-between items-center mt-4">
                                <span>Saldo Anggaran:</span>
                                <span x-text="'Rp ' + formatRibuan(currentSaldo)"></span>
                            </div>
                        </div>

                        <form @submit.prevent="saveReceipt">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Kolom Kiri -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm mb-1 text-gray-300">Tahun Anggaran</label>
                                        <input type="text" :value="setting.tahun_anggaran"
                                            class="w-full border border-gray-600 p-2 rounded bg-gray-900 text-gray-400"
                                            disabled>
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1 text-gray-300">Nomor Bukti</label>
                                        <div class="flex space-x-2">
                                            <input type="text" :value="isEditing ? receipt.nomor_bukti : next_nomor_bukti"
                                                class="w-full border border-gray-600 p-2 rounded bg-gray-900 text-gray-400 font-mono"
                                                disabled>
                                            <button type="button" @click="resetNomorBukti" x-show="!isEditing"
                                                class="bg-gray-700 text-white px-2 rounded shadow hover:bg-gray-600 text-sm"
                                                title="Reset urutan nomor kembali ke 001">Reset</button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1 text-gray-300">Sumber Dana</label>
                                        <input type="text" :value="setting.sumber_dana"
                                            class="w-full border border-gray-600 p-2 rounded bg-gray-900 text-gray-400"
                                            disabled>
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1 text-gray-300">Sudah Terima Dari</label>
                                        <input type="text" x-model="receipt.sudah_terima_dari"
                                            class="w-full border border-gray-600 p-2 rounded bg-gray-900 text-gray-400"
                                            disabled>
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm mb-1 text-gray-300">Nama Penerima Uang</label>
                                        <input type="text" x-model="receipt.nama_penerima"
                                            class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                            placeholder="Kosongkan jika ingin ditulis tangan">
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1 text-gray-300">Tanggal</label>
                                        <div class="relative">
                                            <input type="text" x-ref="datepicker"
                                                class="w-full border border-gray-600 p-2 rounded focus:border-blue-500 focus:ring focus:ring-blue-500 bg-gray-700 text-gray-100"
                                                required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1 text-gray-300">Jumlah Uang (Rp)</label>
                                        <input type="text" x-model="jumlah_format" @input="formatJumlahUang"
                                            class="w-full border border-gray-600 p-2 rounded font-bold bg-gray-700 text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                            required placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1 text-gray-300">Terbilang</label>
                                        <textarea x-model="terbilangText"
                                            class="w-full border border-gray-600 p-2 rounded bg-gray-900 text-sm italic text-gray-400"
                                            rows="1" disabled></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1 text-gray-300">Untuk Pembayaran</label>
                                        <textarea x-model="receipt.untuk_pembayaran"
                                            class="w-full border border-gray-600 p-2 rounded bg-gray-700 text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                            rows="3" required placeholder="Detail pembayaran..."></textarea>
                                    </div>
                                    <div class="mt-6 flex space-x-3">
                                        <button type="submit"
                                            class="bg-green-600 flex-1 text-white px-6 py-2 rounded shadow hover:bg-green-700 disabled:opacity-50 transition-colors"
                                            :disabled="loading || receipt.jumlah == 0">
                                            <span
                                                x-text="loading ? 'Menyimpan...' : (isEditing ? 'Update & Cetak' : 'Simpan & Cetak')"></span>
                                        </button>
                                        <button type="button" x-show="isEditing" @click="cancelEdit"
                                            class="bg-gray-400 text-white px-6 py-2 rounded shadow hover:bg-gray-500 transition-colors">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List -->
                <div>
                    <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700">
                        <h2 class="text-xl font-bold mb-4 text-white">Daftar Kuitansi</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Nomor Bukti</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Tanggal</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Penerima Uang</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Jumlah</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Untuk Pembayaran
                                        </th>
                                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-400">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    <template x-for="item in receipts" :key="item.id">
                                        <tr class="hover:bg-gray-700">
                                            <td class="px-4 py-3 text-sm font-mono text-gray-300" x-text="item.nomor_bukti">
                                            </td>
                                            <td class="px-4 py-3 text-sm"
                                                x-text="item.tanggal.substring(8,10) + '/' + item.tanggal.substring(5,7) + '/' + item.tanggal.substring(0,4)">
                                            </td>
                                            <td class="px-4 py-3 text-sm"
                                                x-text="item.nama_penerima || 'ditulis di tempat'"></td>
                                            <td class="px-4 py-3 text-sm font-medium"
                                                x-text="'Rp ' + formatRibuan(item.jumlah)"></td>
                                            <td class="px-4 py-3 text-sm text-gray-400 truncate max-w-xs"
                                                x-text="item.untuk_pembayaran"></td>
                                            <td class="px-4 py-3 text-sm text-center flex justify-center items-center">
                                                <a :href="'/receipts/' + item.id + '/print'" target="_blank"
                                                    class="text-blue-400 mr-3">Cetak</a>
                                                <button @click="editReceipt(item)"
                                                    class="text-orange-400 mr-3">Edit</button>
                                                <button @click="deleteReceipt(item.id)" class="text-red-400">Hapus</button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="receipts.length === 0">
                                        <td colspan="5" class="px-4 py-4 text-center text-gray-400">Belum ada kuitansi</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kuitansiApp', () => ({
                tab: 'kuitansi',
                loading: false,
                message: '',

                // Server data
                setting: @json($setting),
                receipts: @json($receipts),
                next_nomor_bukti: @json($next_nomor_bukti),
                total_pengeluaran: parseInt(@json($total_pengeluaran)) || 0,

                // Form state
                jumlah_format: '',
                pagu_format: '',
                terbilangText: 'Nol Rupiah',
                isEditing: false,
                isSettingEditing: false,
                originalSetting: {},
                editId: null,
                oldJumlah: 0,
                receipt: {
                    tanggal: new Date().toISOString().split('T')[0],
                    sudah_terima_dari: 'Bendahara Madrasah',
                    jumlah: 0,
                    untuk_pembayaran: '',
                    nama_penerima: ''
                },

                init() {
                    // CSRF setup for Axios
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

                    this.pagu_format = this.formatRibuan(this.setting.total_pagu_anggaran || 0);

                    let self = this;
                    flatpickr(this.$refs.datepicker, {
                        altInput: true,
                        altFormat: "d/m/Y",
                        dateFormat: "Y-m-d",
                        locale: "id",
                        defaultDate: this.receipt.tanggal,
                        onChange: function (selectedDates, dateStr, instance) {
                            self.receipt.tanggal = dateStr;
                        }
                    });

                    // Check for edit_id in URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const editId = urlParams.get('edit_id');
                    if (editId) {
                        const itemToEdit = this.receipts.find(r => r.id == editId);
                        if (itemToEdit) {
                            // Delay slightly to let UI render first
                            setTimeout(() => this.editReceipt(itemToEdit), 100);
                        }
                    }

                    // Check for tab parameter
                    const tabParam = urlParams.get('tab');
                    if (tabParam) {
                        this.tab = tabParam;
                    }
                },

                get currentSaldo() {
                    let totalPagu = parseInt(this.setting.total_pagu_anggaran) || 0;
                    let pengeluaran = parseInt(this.total_pengeluaran) || 0;
                    if (this.isEditing) {
                        pengeluaran -= this.oldJumlah;
                    }
                    let currentJumlah = parseInt(this.receipt.jumlah) || 0;
                    return totalPagu - pengeluaran - currentJumlah;
                },

                formatRibuan(angka) {
                    return new Intl.NumberFormat('id-ID').format(angka);
                },

                formatJumlahUang() {
                    let val = this.jumlah_format.replace(/\D/g, '');
                    if (val === '') {
                        this.receipt.jumlah = 0;
                        this.jumlah_format = '';
                        this.terbilangText = 'Nol Rupiah';
                        return;
                    }
                    this.receipt.jumlah = parseInt(val);
                    this.jumlah_format = this.formatRibuan(this.receipt.jumlah);
                    this.terbilangText = this.jsTerbilang(this.receipt.jumlah);
                },

                formatInputPagu() {
                    let val = this.pagu_format.replace(/\D/g, '');
                    if (val === '') {
                        this.setting.total_pagu_anggaran = 0;
                        this.pagu_format = '';
                        return;
                    }
                    this.setting.total_pagu_anggaran = parseInt(val);
                    this.pagu_format = this.formatRibuan(this.setting.total_pagu_anggaran);
                },

                jsTerbilang(angka) {
                    if (angka === 0) return 'Nol Rupiah';
                    let result = this.jsTerbilangHelper(angka);
                    return result.trim().replace(/\s+/g, ' ') + ' Rupiah';
                },

                jsTerbilangHelper(angka) {
                    let bilangan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
                    angka = Math.abs(angka);
                    let result = "";
                    if (angka < 12) {
                        result = " " + bilangan[angka];
                    } else if (angka < 20) {
                        result = this.jsTerbilangHelper(angka - 10) + " Belas";
                    } else if (angka < 100) {
                        result = this.jsTerbilangHelper(Math.floor(angka / 10)) + " Puluh" + this.jsTerbilangHelper(angka % 10);
                    } else if (angka < 200) {
                        result = " Seratus" + this.jsTerbilangHelper(angka - 100);
                    } else if (angka < 1000) {
                        result = this.jsTerbilangHelper(Math.floor(angka / 100)) + " Ratus" + this.jsTerbilangHelper(angka % 100);
                    } else if (angka < 2000) {
                        result = " Seribu" + this.jsTerbilangHelper(angka - 1000);
                    } else if (angka < 1000000) {
                        result = this.jsTerbilangHelper(Math.floor(angka / 1000)) + " Ribu" + this.jsTerbilangHelper(angka % 1000);
                    } else if (angka < 1000000000) {
                        result = this.jsTerbilangHelper(Math.floor(angka / 1000000)) + " Juta" + this.jsTerbilangHelper(angka % 1000000);
                    } else if (angka < 1000000000000) {
                        result = this.jsTerbilangHelper(Math.floor(angka / 1000000000)) + " Miliar" + this.jsTerbilangHelper(angka % 1000000000);
                    }
                    return result;
                },

                async saveSetting() {
                    this.loading = true;
                    try {
                        const response = await axios.post('/settings', this.setting);
                        if (response.data.success) {
                            this.isSettingEditing = false;
                            Toast.fire({
                                icon: 'success',
                                title: 'Pengaturan berhasil disimpan!'
                            });
                        }
                    } catch (error) {
                        let errMsg = 'Gagal menyimpan pengaturan.';
                        if (error.response && error.response.data && error.response.data.message) {
                            errMsg = error.response.data.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errMsg
                        });
                    }
                    this.loading = false;
                },

                async saveReceipt() {
                    if (!this.setting.nama_madrasah || !this.setting.tahun_anggaran || this.setting.total_pagu_anggaran === null || this.setting.total_pagu_anggaran === undefined || this.setting.total_pagu_anggaran === '' ||
                        !this.setting.alamat || !this.setting.sumber_dana || !this.setting.nama_kepala || !this.setting.nama_bendahara) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Belum Lengkap',
                            text: 'Silakan isi dan simpan SEMUA data pada tab Pengaturan terlebih dahulu sebelum membuat kuitansi.'
                        });
                        this.tab = 'setting';
                        return;
                    }

                    if (this.receipt.jumlah <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Jumlah Tidak Valid',
                            text: 'Jumlah kuitansi harus lebih besar dari 0.'
                        });
                        return;
                    }

                    if (this.currentSaldo < 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Saldo Tidak Cukup',
                            text: 'Tidak dapat membuat kuitansi karena saldo anggaran tidak mencukupi (minus).'
                        });
                        return;
                    }

                    this.loading = true;
                    try {
                        let response;
                        if (this.isEditing) {
                            response = await axios.put('/receipts/' + this.editId, this.receipt);
                        } else {
                            this.receipt.nomor_bukti = this.next_nomor_bukti;
                            response = await axios.post('/receipts', this.receipt);
                        }

                        if (response.data.success) {
                            if (this.isEditing) {
                                let idx = this.receipts.findIndex(r => r.id === this.editId);
                                if (idx !== -1) this.receipts[idx] = response.data.receipt;
                                this.total_pengeluaran = parseInt(this.total_pengeluaran) - parseInt(this.oldJumlah) + parseInt(this.receipt.jumlah);
                            } else {
                                this.receipts.unshift(response.data.receipt);
                                if (this.receipts.length > 5) this.receipts.pop();
                                this.next_nomor_bukti = response.data.next_nomor_bukti;
                                this.total_pengeluaran = parseInt(this.total_pengeluaran) + parseInt(this.receipt.jumlah);
                            }

                            // Buka tab baru untuk print
                            window.open(response.data.print_url, '_blank');

                            // Reset form
                            this.cancelEdit();

                            Toast.fire({
                                icon: 'success',
                                title: this.isEditing ? 'Kuitansi diperbarui!' : 'Kuitansi berhasil dibuat!'
                            });
                        }
                    } catch (error) {
                        if (error.response && error.response.data && error.response.data.message) {
                            Swal.fire('Gagal!', error.response.data.message, 'error');
                        } else {
                            Swal.fire('Terjadi Kesalahan', 'Pastikan semua field wajib terisi.', 'error');
                        }
                    }
                    this.loading = false;
                },

                editReceipt(item) {
                    this.isEditing = true;
                    this.editId = item.id;
                    this.oldJumlah = item.jumlah;

                    this.receipt.tanggal = item.tanggal.substring(0, 10);
                    this.receipt.jumlah = item.jumlah;
                    this.receipt.nama_penerima = item.nama_penerima || '';
                    this.receipt.untuk_pembayaran = item.untuk_pembayaran;
                    this.receipt.sudah_terima_dari = item.sudah_terima_dari;

                    this.jumlah_format = this.formatRibuan(item.jumlah);
                    this.terbilangText = this.jsTerbilang(item.jumlah);

                    this.$refs.datepicker._flatpickr.setDate(this.receipt.tanggal);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                cancelEdit() {
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.has('edit_id')) {
                        window.location.href = '/';
                        return;
                    }
                    this.resetFormState();
                },

                resetFormState() {
                    this.isEditing = false;
                    this.editId = null;
                    this.oldJumlah = 0;

                    this.receipt.sudah_terima_dari = 'Bendahara Madrasah';
                    this.receipt.jumlah = 0;
                    this.jumlah_format = '';
                    this.terbilangText = 'Nol Rupiah';
                    this.receipt.untuk_pembayaran = '';
                    this.receipt.nama_penerima = '';

                    let today = new Date().toISOString().split('T')[0];
                    this.receipt.tanggal = today;
                    if (this.$refs.datepicker && this.$refs.datepicker._flatpickr) {
                        this.$refs.datepicker._flatpickr.setDate(today);
                    }
                },

                editSetting() {
                    this.originalSetting = JSON.parse(JSON.stringify(this.setting));
                    this.isSettingEditing = true;
                },

                cancelSettingEdit() {
                    this.setting = this.originalSetting;
                    this.pagu_format = this.formatRibuan(this.setting.total_pagu_anggaran || 0);
                    this.isSettingEditing = false;
                },

                resetNomorBukti() {
                    if (this.currentSaldo > 0) {
                        Swal.fire('Tidak bisa mereset!', 'Saldo anggaran belum habis (harus 0) sebelum mereset nomor bukti.', 'error');
                        return;
                    }

                    let lastYear = this.receipts.length > 0 ? this.receipts[0].nomor_bukti.split('/').pop() : null;
                    if (lastYear && this.setting.tahun_anggaran == lastYear) {
                        Swal.fire('Tidak bisa mereset!', 'Anda harus mengganti Tahun Anggaran di Pengaturan terlebih dahulu.', 'error');
                        return;
                    }

                    Swal.fire({
                        title: 'Reset Nomor Bukti?',
                        text: 'Yakin ingin mereset urutan nomor bukti kembali ke 001?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Reset!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const year = this.setting.tahun_anggaran || new Date().getFullYear();
                            this.next_nomor_bukti = '001/BOS/' + year;
                            Toast.fire({
                                icon: 'success',
                                title: 'Nomor bukti direset ke 001'
                            });
                        }
                    });
                },

                async deleteReceipt(id) {
                    Swal.fire({
                        title: 'Hapus Kuitansi?',
                        text: 'Yakin ingin menghapus kuitansi ini? Saldo anggaran akan dikembalikan.',
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
                                Swal.fire('Gagal!', 'Gagal menghapus kuitansi.', 'error');
                            }
                        }
                    });
                }
            }));
        });
    </script>
@endsection