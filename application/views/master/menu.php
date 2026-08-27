<section data-view="manajemen-menu" class="hidden">
    <div class="mt-8 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Manajemen Menu</h2><button id="btn-tambah-menu" class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold"><i class="fa-solid fa-plus mr-1"></i> Tambah Menu</button>
    </div>
    <div id="menu-form-wrapper" class="hidden glass p-5 rounded-xl my-5">
        <h3 id="menu-form-title" class="font-bold mb-4">Tambah Menu</h3>
        <form id="menu-form"><input id="menu-id" type="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label>Menu Induk</label>
                    <select id="menu-parent-id" class="input">
                        <option value="">-- Menu Utama --</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Kosongkan jika ini adalah menu utama.
                    </p>
                </div>
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
                <p class="font-semibold text-sm mb-2">
                    Tampilkan untuk peran:
                </p>

                <div id="menu-role-list"
                    class="flex flex-wrap gap-x-5 gap-y-2">
                    <!-- Role diisi dari Master Role -->
                </div>

                <label class="inline-flex items-center gap-2 mt-3">
                    <input id="menu-active" type="checkbox" checked>
                    Aktif
                </label>
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