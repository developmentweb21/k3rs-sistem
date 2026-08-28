<section data-view="dashboard">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold">Dashboard K3RS</h2>
            <p class="text-sm text-gray-500">
                Monitoring laporan Insiden, Kesehatan, dan Checklist K3.
            </p>
        </div>

        <button
            type="button"
            id="btn-refresh-dashboard"
            class="button bg-blue-600">
            <i class="fa-solid fa-rotate-right mr-2"></i>
            Refresh
        </button>
    </div>


    <!-- FILTER -->
    <div class="glass p-5 rounded-xl mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

            <div>
                <label class="block text-sm font-medium mb-2">
                    Periode
                </label>

                <input
                    type="month"
                    id="filter-dashboard-periode"
                    class="input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">
                    Unit Kerja
                </label>

                <select
                    id="filter-dashboard-unit"
                    class="input w-full">
                    <option value="">Semua Unit</option>
                </select>
            </div>

            <div>
                <button
                    type="button"
                    id="btn-filter-dashboard"
                    class="button bg-teal-600 w-full">
                    <i class="fa-solid fa-filter mr-2"></i>
                    Terapkan Filter
                </button>
            </div>

        </div>
    </div>


    <!-- CARD UTAMA -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

        <!-- INSIDEN -->
        <div class="glass rounded-xl p-6 border-l-4 border-red-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">
                        Laporan Insiden
                    </p>

                    <h3
                        id="dashboard-insiden-total"
                        class="text-3xl font-bold mt-2">
                        0
                    </h3>
                </div>

                <div class="text-red-500 text-2xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 mt-5 text-center text-sm">
                <div>
                    <strong id="dashboard-insiden-menunggu">0</strong>
                    <p class="text-gray-500">Menunggu</p>
                </div>

                <div>
                    <strong id="dashboard-insiden-proses">0</strong>
                    <p class="text-gray-500">Proses</p>
                </div>

                <div>
                    <strong id="dashboard-insiden-selesai">0</strong>
                    <p class="text-gray-500">Selesai</p>
                </div>
            </div>
        </div>


        <!-- KESEHATAN -->
        <div class="glass rounded-xl p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">
                        Laporan Kesehatan
                    </p>

                    <h3
                        id="dashboard-kesehatan-total"
                        class="text-3xl font-bold mt-2">
                        0
                    </h3>
                </div>

                <div class="text-blue-500 text-2xl">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
            </div>

            <p class="text-sm text-gray-500 mt-5">
                Total laporan kesehatan pada periode yang dipilih.
            </p>
        </div>


        <!-- CHECKLIST -->
        <div class="glass rounded-xl p-6 border-l-4 border-teal-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">
                        Checklist K3
                    </p>

                    <h3
                        id="dashboard-checklist-total"
                        class="text-3xl font-bold mt-2">
                        0
                    </h3>
                </div>

                <div class="text-teal-500 text-2xl">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
            </div>

            <div class="mt-5">
                <div class="flex justify-between text-sm mb-2">
                    <span>Kepatuhan</span>
                    <strong id="dashboard-checklist-kepatuhan">
                        0%
                    </strong>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div
                        id="dashboard-checklist-progress"
                        class="bg-teal-500 h-2 rounded-full"
                        style="width: 0%"></div>
                </div>

                <div class="flex justify-between text-xs text-gray-500 mt-3">
                    <span>
                        Sesuai:
                        <strong id="dashboard-checklist-sesuai">0</strong>
                    </span>

                    <span>
                        Tidak Sesuai:
                        <strong id="dashboard-checklist-tidak-sesuai">0</strong>
                    </span>
                </div>
            </div>
        </div>

    </div>
    <!-- KEPATUHAN PELAPORAN UNIT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- RINGKASAN -->
        <div class="glass rounded-xl p-6">

            <h3 class="font-bold text-lg mb-5">
                <i class="fa-solid fa-building-circle-check text-teal-600 mr-2"></i>
                Kepatuhan Pelaporan Unit
            </h3>

            <div class="text-center mb-6">
                <div
                    id="dashboard-unit-persentase"
                    class="text-4xl font-bold text-teal-600">
                    0%
                </div>

                <p class="text-sm text-gray-500 mt-2">
                    Unit yang sudah melapor
                </p>
            </div>

            <div class="grid grid-cols-3 gap-3 text-center">

                <div>
                    <strong
                        id="dashboard-unit-total"
                        class="text-lg">
                        0
                    </strong>

                    <p class="text-xs text-gray-500">
                        Total Unit
                    </p>
                </div>

                <div>
                    <strong
                        id="dashboard-unit-sudah"
                        class="text-lg text-green-600">
                        0
                    </strong>

                    <p class="text-xs text-gray-500">
                        Sudah Lapor
                    </p>
                </div>

                <div>
                    <strong
                        id="dashboard-unit-belum"
                        class="text-lg text-red-600">
                        0
                    </strong>

                    <p class="text-xs text-gray-500">
                        Belum Lapor
                    </p>
                </div>

            </div>

        </div>

        <!-- CHART -->
        <div class="glass rounded-xl p-6 lg:col-span-2">

            <h3 class="font-bold text-lg mb-5">
                <i class="fa-solid fa-chart-column text-blue-600 mr-2"></i>
                Status Pelaporan Checklist per Unit
            </h3>

            <div style="height: 320px">
                <canvas id="chart-kepatuhan-unit"></canvas>
            </div>

        </div>

    </div>

</section>
<section data-view="riwayat" class="hidden">
    <h2 class="text-2xl font-bold mb-6">Riwayat Laporan Insiden</h2>
    <div class="glass overflow-x-auto rounded-xl">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4">Tanggal</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Pelapor</th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>
            <tbody id="table-riwayat-insiden">
                <tr>
                    <td class="p-4 text-gray-500" colspan="5">Memuat riwayat laporan...</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<section data-view="verifikasi" class="hidden">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Verifikasi Laporan Insiden</h2>
        <span class="text-sm text-gray-500">
            Laporan yang menunggu verifikasi
        </span>
    </div>
    <!-- FILTER STATUS VERIFIKASI -->
    <div class="flex flex-wrap gap-2 mb-5">

        <button
            type="button"
            class="btn-filter-verifikasi px-4 py-2 rounded-lg
               bg-blue-600 text-white font-medium"
            data-status="">
            Semua
        </button>

        <button
            type="button"
            class="btn-filter-verifikasi px-4 py-2 rounded-lg
               bg-yellow-100 text-yellow-700 font-medium"
            data-status="menunggu">
            Menunggu
        </button>

        <button
            type="button"
            class="btn-filter-verifikasi px-4 py-2 rounded-lg
               bg-blue-100 text-blue-700 font-medium"
            data-status="diproses">
            Diproses
        </button>

        <button
            type="button"
            class="btn-filter-verifikasi px-4 py-2 rounded-lg
               bg-green-100 text-green-700 font-medium"
            data-status="selesai">
            Selesai
        </button>

    </div>

    <div class="glass overflow-x-auto rounded-xl">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4">Tanggal</th>
                    <th>Pelapor</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="table-verifikasi">
                <tr>
                    <td colspan="6" class="p-4 text-gray-500">
                        Memuat laporan...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<section data-view="detail-verifikasi" class="hidden">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
        <div>
            <button
                type="button"
                id="btn-kembali-verifikasi"
                class="text-blue-600 hover:text-blue-800 mb-2">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Verifikasi
            </button>

            <h2 class="text-2xl font-bold">Detail Laporan Insiden</h2>
            <p class="text-sm text-gray-500">
                Periksa informasi laporan sebelum melakukan verifikasi.
            </p>
        </div>

        <div>
            <span
                id="detail-verifikasi-status"
                class="badge bg-yellow-100 text-yellow-700">
                Menunggu
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Informasi Utama -->
        <div class="lg:col-span-2 space-y-6">

            <div class="glass rounded-xl p-6">
                <h3 class="font-bold text-lg mb-5">
                    <i class="fa-solid fa-file-lines text-blue-600 mr-2"></i>
                    Informasi Laporan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Kejadian</p>
                        <p id="detail-verifikasi-tanggal" class="font-medium">-</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Kategori</p>
                        <p id="detail-verifikasi-kategori" class="font-medium">-</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Lokasi Kejadian</p>
                        <p id="detail-verifikasi-lokasi" class="font-medium">-</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Dilaporkan</p>
                        <p id="detail-verifikasi-created" class="font-medium">-</p>
                    </div>

                </div>
            </div>

            <div class="glass rounded-xl p-6">
                <h3 class="font-bold text-lg mb-4">
                    <i class="fa-solid fa-align-left text-blue-600 mr-2"></i>
                    Kronologi Kejadian
                </h3>

                <div
                    id="detail-verifikasi-kronologi"
                    class="text-gray-700 whitespace-pre-line leading-relaxed">
                    -
                </div>
            </div>

            <div class="glass rounded-xl p-6">
                <h3 class="font-bold text-lg mb-4">
                    <i class="fa-solid fa-shield-heart text-blue-600 mr-2"></i>
                    Tindakan Awal
                </h3>

                <div
                    id="detail-verifikasi-tindakan"
                    class="text-gray-700 whitespace-pre-line leading-relaxed">
                    -
                </div>
            </div>
            <!-- RIWAYAT TINDAK LANJUT -->
            <div
                id="detail-tindak-lanjut-card"
                class="glass rounded-xl p-6 hidden">
                <h3 class="font-bold text-lg mb-5">
                    <i class="fa-solid fa-clipboard-list text-blue-600 mr-2"></i>
                    Tindak Lanjut Verifikasi
                </h3>

                <div
                    id="detail-tindak-lanjut-list"
                    class="space-y-4">
                    <!-- Diisi oleh JavaScript -->
                </div>
            </div>

        </div>

        <!-- MODAL EDIT TINDAK LANJUT -->
        <div
            id="modal-edit-tindak-lanjut"
            class="fixed inset-0 bg-black/50 hidden
           items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl w-full max-w-2xl p-6">

                <div class="flex justify-between items-center mb-5">
                    <h3 class="font-bold text-lg">
                        Edit Tindak Lanjut
                    </h3>

                    <button
                        type="button"
                        data-close-edit-tindak-lanjut>
                        Batal
                    </button>
                </div>

                <input
                    type="hidden"
                    id="edit-tindak-lanjut-id">

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">
                        Keterangan / Tindakan Lanjutan
                        <span class="text-red-500">*</span>
                    </label>

                    <textarea
                        id="edit-tindak-lanjut-keterangan"
                        rows="5"
                        class="w-full border rounded-lg p-3"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">
                        Ganti Foto Dokumentasi
                        <span class="text-gray-400">(Opsional)</span>
                    </label>

                    <input
                        type="file"
                        id="edit-tindak-lanjut-foto"
                        accept="image/jpeg,image/png"
                        class="w-full border rounded-lg p-2">
                </div>

                <div
                    id="edit-tindak-lanjut-foto-lama"
                    class="mb-4"></div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        data-close-edit-tindak-lanjut
                        class="text-gray-500 hover:text-gray-700">

                        <button
                            type="button"
                            id="btn-update-tindak-lanjut"
                            class="px-5 py-2 bg-blue-600
                       hover:bg-blue-700 text-white rounded-lg">
                            <i class="fa-solid fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                </div>

            </div>
        </div>

        <!-- Informasi Pelapor -->
        <div class="space-y-6">

            <div class="glass rounded-xl p-6">
                <h3 class="font-bold text-lg mb-5">
                    <i class="fa-solid fa-user text-blue-600 mr-2"></i>
                    Informasi Pelapor
                </h3>

                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-full bg-blue-100 text-blue-600
                               flex items-center justify-center text-xl">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div>
                        <p id="detail-verifikasi-pelapor" class="font-semibold">
                            -
                        </p>
                        <p class="text-sm text-gray-500">
                            Pelapor Insiden
                        </p>
                    </div>
                </div>
            </div>

            <div class="glass rounded-xl p-6">
                <h3 class="font-bold text-lg mb-4">
                    Status Laporan
                </h3>

                <p
                    id="detail-verifikasi-status-info"
                    class="text-sm text-gray-500 mb-3">
                    -
                </p>

                <div class="border-t pt-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">ID Laporan</span>
                        <span id="detail-verifikasi-id" class="font-medium">-</span>
                    </div>
                </div>
            </div>
            <!-- PROSES VERIFIKASI -->
            <div class="glass rounded-xl p-6">

                <h3 class="font-bold text-lg mb-2">
                    <i class="fa-solid fa-check-circle text-green-600 mr-2"></i>
                    Proses Verifikasi
                </h3>

                <p
                    id="detail-verifikasi-action-info"
                    class="text-sm text-gray-500 mb-4">
                    -
                </p>

                <button
                    type="button"
                    id="btn-verifikasi-laporan"
                    class="w-full bg-green-600 hover:bg-green-700 text-white
               font-medium py-3 px-4 rounded-lg transition">

                    <i class="fa-solid fa-check mr-2"></i>
                    Verifikasi Laporan

                </button>

            </div>

        </div>

    </div>
    <!-- FORM TINDAK LANJUT VERIFIKASI -->
    <div
        id="form-tindak-lanjut"
        class="glass rounded-xl p-6 mt-4 hidden">
        <h3 class="font-bold text-lg mb-4">
            <i class="fa-solid fa-clipboard-check text-blue-600 mr-2"></i>
            Form Tindak Lanjut Verifikasi
        </h3>

        <!-- KETERANGAN -->
        <div class="mb-4">
            <label
                for="tindak-lanjut-keterangan"
                class="block text-sm font-medium text-gray-700 mb-2">
                Keterangan / Tindakan Lanjutan
                <span class="text-red-500">*</span>
            </label>

            <textarea
                id="tindak-lanjut-keterangan"
                rows="5"
                class="w-full border border-gray-300 rounded-lg p-3
                   focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Tuliskan hasil verifikasi atau tindakan lanjutan yang dilakukan..."></textarea>
        </div>

        <!-- FOTO -->
        <div class="mb-5">
            <label
                for="tindak-lanjut-foto"
                class="block text-sm font-medium text-gray-700 mb-2">
                Foto Dokumentasi
                <span class="text-gray-400">(Opsional)</span>
            </label>

            <input
                type="file"
                id="tindak-lanjut-foto"
                accept="image/*"
                class="w-full border border-gray-300 rounded-lg p-2">

            <p class="text-xs text-gray-400 mt-2">
                Format gambar: JPG, JPEG, PNG. Maksimal 5 MB.
            </p>
        </div>

        <!-- TOMBOL -->
        <div class="flex gap-3 justify-end">

            <button
                type="button"
                id="btn-batal-tindak-lanjut"
                class="px-4 py-2 rounded-lg border border-gray-300
                   hover:bg-gray-50">
                Batal
            </button>

            <button
                type="button"
                id="btn-simpan-tindak-lanjut"
                class="px-5 py-2 rounded-lg bg-green-600
                   hover:bg-green-700 text-white font-medium">
                <i class="fa-solid fa-save mr-2"></i>
                Simpan & Mulai Proses
            </button>

        </div>
    </div>

</section>
<section data-view="laporan" class="hidden">
    <h2 class="text-2xl font-bold mb-6">Rekap Laporan K3RS</h2>
    <div class="glass p-5 rounded-xl">Rekap laporan akan ditampilkan dari modul Laporan.</div>
</section>