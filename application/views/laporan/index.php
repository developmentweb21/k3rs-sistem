<section data-view="dashboard">
    <h2 class="text-2xl font-bold mb-6">KPI Dashboard K3RS</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <article class="card border-l-4 border-red-500">
            <p>Total Kejadian K3</p><strong id="kpi-total"></strong>
        </article>
        <article class="card border-l-4 border-yellow-500">
            <p>Near Miss</p><strong id="kpi-near-miss"></strong>
        </article>
        <article class="card border-l-4 border-purple-500">
            <p>Insiden B3</p><strong id="kpi-b3"></strong>
        </article>
        <article class="card border-l-4 border-teal-500">
            <p>Kepatuhan Unit</p><strong id="kpi-kepatuhan"></strong>
        </article>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass p-5 rounded-xl">
            <h3 class="font-bold mb-4">Tren Insiden</h3><canvas id="chart-insiden"></canvas>
        </div>
        <div class="glass p-5 rounded-xl">
            <h3 class="font-bold mb-4">Kategori Insiden</h3><canvas id="chart-kategori"></canvas>
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

                <p class="text-sm text-gray-500 mb-3">
                    Laporan ini saat ini menunggu proses verifikasi.
                </p>

                <div class="border-t pt-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">ID Laporan</span>
                        <span id="detail-verifikasi-id" class="font-medium">-</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</section>
<section data-view="laporan" class="hidden">
    <h2 class="text-2xl font-bold mb-6">Rekap Laporan K3RS</h2>
    <div class="glass p-5 rounded-xl">Rekap laporan akan ditampilkan dari modul Laporan.</div>
</section>