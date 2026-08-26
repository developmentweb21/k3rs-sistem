<div id="app-container">
    <aside id="desktop-sidebar" class="glass-sidebar sidebar fixed h-full text-white flex flex-col justify-between">
        <div>
            <div class="sidebar-brand">
                <span class="sidebar-logo"><i class="fa-solid fa-shield-halved"></i></span>
                <div><h2>K3RS Portal</h2><p>Sistem Pelaporan RS</p></div>
            </div>
            <div class="sidebar-user"><span class="sidebar-avatar"><i class="fa-solid fa-user"></i></span><div><p id="sidebar-user-name">Pengguna</p><small id="sidebar-user-role">USER</small></div></div>
            <p class="sidebar-label">MENU UTAMA</p>
            <nav class="sidebar-nav" id="nav-menu" aria-label="Navigasi utama"></nav>
        </div>
        <div class="sidebar-footer"><button id="logout" class="logout-button"><i class="fa-solid fa-right-from-bracket"></i> Keluar dari sistem</button></div>
    </aside>
    <main id="main-content" class="ml-64 p-4 md:p-8 min-h-screen">
        <?php $this->load->view('laporan/index'); ?>
        <?php $this->load->view('transaksi/index'); ?>
        <?php $this->load->view('master/index'); ?>
        <?php $this->load->view('master/roles'); ?>
    </main>
    <nav id="bottom-nav" class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 z-50"></nav>
</div>
<div id="toast-container"></div>
