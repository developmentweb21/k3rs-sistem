<section data-view="setting" class="hidden">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold">Pengaturan Aplikasi</h2>
                <p class="text-sm text-gray-500">Atur identitas aplikasi, alamat, logo, header, dan footer.</p>
            </div>
        </div>

        <div class="glass rounded-xl p-6">
            <form id="setting-form">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Aplikasi</label>
                        <input id="setting-app-name" class="input" type="text" placeholder="Contoh: SIRAMA" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea id="setting-alamat" class="input" rows="3" placeholder="Contoh: Jl. Cendrawasih No. 12, Kota RS"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo Aplikasi</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-lg border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden">
                                <img id="setting-logo-preview" class="hidden w-full h-full object-cover" alt="Logo aplikasi">
                                <i id="setting-logo-placeholder" class="fa-solid fa-image text-2xl text-gray-400"></i>
                            </div>
                            <input id="setting-logo-file" type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon Aplikasi</label>
                        <input id="setting-icon" class="input" type="text" placeholder="Contoh: fa-shield-halved" value="fa-shield-halved">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Header</label>
                        <input id="setting-header-text" class="input" type="text" placeholder="Sistem Pelaporan RS">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Footer</label>
                        <input id="setting-footer-text" class="input" type="text" placeholder="@2026 SIRAMA by Saleh Mahmud">
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>