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
    <div class="mt-8 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Manajemen Menu</h2><button id="btn-tambah-menu" class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold"><i class="fa-solid fa-plus mr-1"></i> Tambah Menu</button>
    </div>
    <div id="menu-form-wrapper" class="hidden glass p-5 rounded-xl my-5">
        <h3 id="menu-form-title" class="font-bold mb-4">Tambah Menu</h3>
        <form id="menu-form"><input id="menu-id" type="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label>Nama Menu</label><input id="menu-nama" class="input" required></div>
                <div><label>Slug / View</label><input id="menu-slug" class="input" placeholder="contoh: dashboard" required></div>
                <div><label>Icon Menu</label>
                    <div class="flex gap-2"><input id="menu-icon" class="input" readonly required><button id="btn-pilih-icon" type="button" class="bg-gray-200 px-3 rounded-md" title="Pilih ikon"><i id="menu-icon-preview" class="fa-solid fa-circle"></i></button></div>
                </div>
                <div><label>Urutan</label><input id="menu-urutan" type="number" min="0" class="input" value="0" required></div>
            </div>
            <div id="icon-picker" class="hidden mt-4 p-4 bg-gray-50 border rounded-lg">
                <p class="text-sm font-semibold mb-3">Pilih ikon menu</p>
                <div class="grid grid-cols-6 sm:grid-cols-10 gap-2"><button type="button" class="icon-choice" data-icon="fa-chart-line"><i class="fa-solid fa-chart-line"></i></button><button type="button" class="icon-choice" data-icon="fa-house"><i class="fa-solid fa-house"></i></button><button type="button" class="icon-choice" data-icon="fa-triangle-exclamation"><i class="fa-solid fa-triangle-exclamation"></i></button><button type="button" class="icon-choice" data-icon="fa-notes-medical"><i class="fa-solid fa-notes-medical"></i></button><button type="button" class="icon-choice" data-icon="fa-list-check"><i class="fa-solid fa-list-check"></i></button><button type="button" class="icon-choice" data-icon="fa-clock-rotate-left"><i class="fa-solid fa-clock-rotate-left"></i></button><button type="button" class="icon-choice" data-icon="fa-clipboard-check"><i class="fa-solid fa-clipboard-check"></i></button><button type="button" class="icon-choice" data-icon="fa-users"><i class="fa-solid fa-users"></i></button><button type="button" class="icon-choice" data-icon="fa-user-gear"><i class="fa-solid fa-user-gear"></i></button><button type="button" class="icon-choice" data-icon="fa-database"><i class="fa-solid fa-database"></i></button><button type="button" class="icon-choice" data-icon="fa-file-lines"><i class="fa-solid fa-file-lines"></i></button><button type="button" class="icon-choice" data-icon="fa-folder-open"><i class="fa-solid fa-folder-open"></i></button><button type="button" class="icon-choice" data-icon="fa-building"><i class="fa-solid fa-building"></i></button><button type="button" class="icon-choice" data-icon="fa-shield-halved"><i class="fa-solid fa-shield-halved"></i></button><button type="button" class="icon-choice" data-icon="fa-gear"><i class="fa-solid fa-gear"></i></button><button type="button" class="icon-choice" data-icon="fa-circle"><i class="fa-solid fa-circle"></i></button></div>
            </div>
            <div class="mt-4">
                <p class="font-semibold text-sm mb-2">Tampilkan untuk peran:</p><label class="mr-5"><input id="menu-role-admin" type="checkbox" value="admin"> Administrator</label><label><input id="menu-role-user" type="checkbox" value="user"> Petugas Unit</label><label class="ml-5"><input id="menu-active" type="checkbox" checked> Aktif</label>
            </div>
            <div class="mt-5 flex gap-3"><button class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold">Simpan Menu</button><button id="btn-batal-menu" type="button" class="bg-gray-200 px-4 py-2 rounded-md font-semibold">Batal</button></div>
        </form>
    </div>
    <div class="glass overflow-x-auto rounded-xl mt-5">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4">Urutan</th>
                    <th>Menu</th>
                    <th>Slug</th>
                    <th>Peran</th>
                    <th>Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-menu">
                <tr>
                    <td class="p-4" colspan="6">Memuat menu...</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>