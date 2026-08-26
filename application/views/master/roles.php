<section data-view="role-akses" class="hidden">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Peran &amp; Akses</h2><button id="btn-tambah-role" class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold"><i class="fa-solid fa-plus mr-1"></i> Tambah Peran</button>
    </div>
    <div id="role-form-wrapper" class="hidden glass p-5 rounded-xl my-5">
        <h3 id="role-form-title" class="font-bold mb-4">Tambah Peran</h3>
        <form id="role-form"><input id="role-id" type="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label>Nama Peran</label><input id="role-nama" class="input" placeholder="Contoh: Kepala Unit" required></div>
                <div><label>Kode Peran</label><input id="role-kode" class="input" placeholder="contoh: kepala_unit" required></div>
            </div>
            <p class="mt-2 text-xs text-gray-500">Kode digunakan sistem untuk akses menu; gunakan huruf kecil, angka, strip atau garis bawah.</p>
            <div class="mt-5 flex gap-3"><button class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold">Simpan Peran</button><button id="btn-batal-role" type="button" class="bg-gray-200 px-4 py-2 rounded-md font-semibold">Batal</button></div>
        </form>
    </div>
    <div class="glass overflow-x-auto rounded-xl mt-5">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4">Nama Peran</th>
                    <th>Kode</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-role">
                <tr>
                    <td class="p-4" colspan="3">Memuat peran...</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>