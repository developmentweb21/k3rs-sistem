<section data-view="pegawai" class="hidden">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manajemen Pegawai</h2><button id="btn-tambah-pegawai" class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold"><i class="fa-solid fa-plus mr-1"></i> Tambah Pegawai</button>
    </div>
    <div id="pegawai-form-wrapper" class="hidden glass p-5 rounded-xl mb-5">
        <h3 id="pegawai-form-title" class="font-bold mb-4">Tambah Pegawai</h3>
        <form id="pegawai-form"><input id="pegawai-id" type="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label>Username</label><input id="pegawai-username" class="input" required></div>
                <div><label>Nama Lengkap</label><input id="pegawai-nama" class="input" required></div>
                <div><label>Unit Kerja</label><select id="pegawai-unit" class="input" required></select></div>
                <div><label>Peran</label><select id="pegawai-role" class="input">
                        <option value="user">Petugas Unit</option>
                        <option value="admin">Administrator</option>
                    </select></div>
                <div class="md:col-span-2"><label>Password <span class="text-xs text-gray-500">(wajib saat tambah; kosongkan saat edit)</span></label><input id="pegawai-password" type="password" class="input"></div>
            </div>
            <div class="mt-5 flex gap-3"><button class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold">Simpan</button><button id="btn-batal-pegawai" type="button" class="bg-gray-200 px-4 py-2 rounded-md font-semibold">Batal</button></div>
        </form>
    </div>
    <div class="glass overflow-x-auto rounded-xl">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4">Username</th>
                    <th>Nama Pegawai</th>
                    <th>Unit Kerja</th>
                    <th>Peran</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-pegawai">
                <tr>
                    <td class="p-4" colspan="5">Memuat data pegawai...</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<section data-view="master" class="hidden">
    <h2 class="text-2xl font-bold mb-6">Master Data K3RS</h2>
    <div id="master-form-wrapper" class="hidden glass p-5 rounded-xl mb-5">
        <h3 id="master-form-title" class="font-bold mb-4">Tambah Data Master</h3>
        <form id="master-form"><input id="master-id" type="hidden">
            <input id="master-jenis" type="hidden"><label>Nama</label>
            <input id="master-nama" class="input" required>
            <div class="mt-4 flex gap-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold">Simpan</button>
                <button id="btn-batal-master" type="button" class="bg-gray-200 px-4 py-2 rounded-md font-semibold">Batal</button>
            </div>
        </form>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="glass p-5 rounded-xl">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold">Kategori Kejadian</h3>
                <button class="text-blue-600" data-tambah-master="kategori">
                    <i class="fa-solid fa-plus">

                    </i></button>
            </div>
            <ul id="master-kategori" class="text-sm space-y-2 text-gray-600"></ul>
        </div>
        <div class="glass p-5 rounded-xl">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold">Unit Kerja</h3>
                <button class="text-blue-600" data-tambah-master="unit"><i class="fa-solid fa-plus"></i></button>
            </div>
            <ul id="master-unit" class="text-sm space-y-2 text-gray-600"></ul>
        </div>
        <div class="glass p-5 rounded-xl">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold">Checklist Kepatuhan</h3><button class="text-blue-600" data-tambah-master="checklist"><i class="fa-solid fa-plus"></i></button>
            </div>
            <ul id="master-checklist" class="text-sm space-y-2 text-gray-600"></ul>
        </div>
    </div>

</section>