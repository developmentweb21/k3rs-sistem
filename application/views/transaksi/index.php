<section data-view="insiden" class="hidden max-w-2xl mx-auto">
    <div class="glass p-6 rounded-xl">
        <h2 class="text-2xl font-bold mb-4">Form Laporan Insiden K3</h2>
        <form class="report-form" data-type="insiden">
            <label>Kategori Kejadian</label>
            <select id="lap-kategori" class="input" required></select>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
                <input type="date" class="input" required><input class="input" placeholder="Lokasi kejadian" required>
            </div><label>Kronologi Singkat</label>
            <textarea class="input" rows="4" required></textarea>
            <label class="mt-4 block">Tindakan Pertama</label><input class="input mb-5" required>
            <button class="button bg-blue-600">Kirim Laporan</button>
        </form>
    </div>
</section>
<section data-view="kesehatan" class="hidden max-w-2xl mx-auto">
    <div class="glass p-6 rounded-xl">
        <h2 class="text-2xl font-bold mb-2">Laporan Kesehatan Karyawan</h2>
        <form class="report-form" data-type="kesehatan">
            <label>Unit Kerja</label><input id="lap-sehat-unit" class="input bg-gray-100" readonly>
            <label class="mt-4 block">Nama Karyawan</label><select id="lap-sehat-nama" class="input" required>
            </select><label class="mt-4 block">Diagnosa / Keluhan</label><input class="input" required>
            <label class="mt-4 block">Hari Tidak Masuk</label>
            <input type="number" min="0" value="0" class="input mb-5" required>
            <button class="button bg-green-600">Kirim Data Kesehatan</button>
        </form>
    </div>
</section>
<section data-view="checklist" class="hidden max-w-3xl mx-auto">
    <div class="glass p-6 rounded-xl">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-teal-100
                        flex items-center justify-center text-teal-600">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>

            <div>
                <h2 class="text-2xl font-bold">
                    Checklist Kepatuhan K3
                </h2>
                <p class="text-sm text-gray-500">
                    Silakan pilih Ya atau Tidak pada setiap item checklist.
                </p>
            </div>
        </div>

        <form class="report-form" data-type="checklist">

            <!-- Periode dan Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

                <div>
                    <label class="block mb-2 text-sm font-medium">
                        Periode
                    </label>
                    <input
                        type="month"
                        id="check-periode"
                        class="input w-full"
                        required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium">
                        Tanggal Pengisian
                    </label>
                    <input
                        type="date"
                        class="input"
                        required>
                </div>

            </div>

            <!-- Unit -->
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium">
                    Unit Kerja
                </label>

                <select
                    id="check-unit"
                    class="input"
                    required></select>
            </div>

            <!-- Daftar Checklist -->
            <div class="mb-3">
                <h3 class="font-semibold text-gray-800">
                    Item Checklist
                </h3>
                <p class="text-sm text-gray-500">
                    Semua item wajib dijawab.
                </p>
            </div>

            <div
                id="checklist-items"
                class="space-y-3 my-5">
                <div class="text-center text-gray-400 py-6">
                    Memuat item checklist...
                </div>
            </div>

            <!-- Tombol -->
            <div class="pt-3 border-t">
                <button
                    type="submit"
                    class="button bg-teal-600 hover:bg-teal-700">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Simpan Checklist
                </button>
            </div>

        </form>
    </div>
</section>
<section data-view="riwayat-checklist" class="hidden max-w-5xl mx-auto">
    <div class="glass p-6 rounded-xl">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold">
                    Riwayat Checklist K3
                </h2>
                <p class="text-sm text-gray-500">
                    Daftar checklist K3 yang telah dikirim.
                </p>
            </div>

            <button
                type="button"
                class="button bg-teal-600"
                onclick="view('checklist')">
                <i class="fa-solid fa-plus mr-2"></i>
                Buat Checklist
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-3">Tanggal</th>
                        <th class="text-left p-3">Periode</th>
                        <th class="text-left p-3">Unit Kerja</th>
                        <th class="text-center p-3">Sesuai</th>
                        <th class="text-center p-3">Tidak Sesuai</th>
                        <th class="text-center p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody id="table-riwayat-checklist">
                    <tr>
                        <td colspan="6" class="text-center text-gray-400 p-6">
                            Memuat riwayat checklist...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</section>
<section data-view="detail-checklist" class="hidden max-w-4xl mx-auto">
    <div class="glass p-6 rounded-xl">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold">
                    Detail Checklist K3
                </h2>
                <p class="text-sm text-gray-500">
                    Hasil pemeriksaan checklist K3.
                </p>
            </div>

            <button
                type="button"
                class="button bg-gray-600"
                id="btn-kembali-riwayat-checklist">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Kembali
            </button>
        </div>

        <!-- Informasi laporan -->
        <div
            id="detail-checklist-info"
            class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6"></div>

        <!-- Detail item -->
        <div>
            <h3 class="font-semibold text-lg mb-4">
                Hasil Checklist
            </h3>

            <div
                id="detail-checklist-items"
                class="space-y-3">
                <div class="text-center text-gray-400 py-6">
                    Memuat detail checklist...
                </div>
            </div>
        </div>

    </div>
</section>