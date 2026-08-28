<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_model extends CI_Model
{
    public function get_nama_list($table)
    {
        $allowed = array('master_kategori', 'master_unit', 'master_checklist');
        if (!in_array($table, $allowed, TRUE)) return array();
        return array_column($this->db->select('nama')->order_by('nama')->get($table)->result_array(), 'nama');
    }
    public function table_master($jenis)
    {
        $tables = array('kategori' => 'master_kategori', 'unit' => 'master_unit', 'checklist' => 'master_checklist');
        return isset($tables[$jenis]) ? $tables[$jenis] : NULL;
    }
    public function get_master($jenis)
    {
        $table = $this->table_master($jenis);
        return $table ? $this->db->select('id, nama')->order_by('nama')->get($table)->result_array() : array();
    }
    public function save_master($jenis, $data)
    {
        $table = $this->table_master($jenis);
        $id = (int) ($data['id'] ?? 0);
        $nama = trim($data['nama'] ?? '');
        if (!$table) return array('success' => FALSE, 'message' => 'Jenis master tidak valid.');
        if ($nama === '') return array('success' => FALSE, 'message' => 'Nama data master wajib diisi.');
        $this->db->where('nama', $nama);
        if ($id) $this->db->where('id !=', $id);
        if ($this->db->count_all_results($table) > 0) return array('success' => FALSE, 'message' => 'Data tersebut sudah tersedia.');
        if ($id) {
            $this->db->where('id', $id)->update($table, array('nama' => $nama));
            return array('success' => TRUE, 'message' => 'Data master berhasil diperbarui.');
        }
        $this->db->insert($table, array('nama' => $nama));
        return array('success' => TRUE, 'message' => 'Data master berhasil ditambahkan.');
    }
    public function delete_master($jenis, $id)
    {
        $table = $this->table_master($jenis);
        if (!$table) return FALSE;
        $this->db->where('id', (int) $id)->delete($table);
        return $this->db->affected_rows() > 0;
    }
    public function get_navigation($role)
    {
        return $this->db->select('menus.id, menus.parent_id, menus.slug, menus.nama, menus.icon')
            ->from('menus')->join('menu_roles', 'menu_roles.menu_id = menus.id')
            ->where('menu_roles.role', $role)->where('menus.is_active', 1)
            ->order_by('menus.urutan')->get()->result_array();
    }
    public function role_has_menu_access($role, $slug)
    {
        if (empty($role) || empty($slug)) {
            return FALSE;
        }

        return $this->db
            ->from('menus')
            ->join(
                'menu_roles',
                'menu_roles.menu_id = menus.id'
            )
            ->where('menu_roles.role', $role)
            ->where('menus.slug', $slug)
            ->where('menus.is_active', 1)
            ->count_all_results() > 0;
    }
    public function get_menus()
    {
        $menus = $this->db->select(
            'menus.*, parent.nama AS parent_nama'
        )->from('menus')->join('menus parent', 'parent.id = menus.parent_id', 'left')->order_by('menus.urutan')->get()->result_array();
        foreach ($menus as &$menu) {
            $menu['roles'] = array_column($this->db->select('role')->where('menu_id', $menu['id'])->get('menu_roles')->result_array(), 'role');
        }
        return $menus;
    }
    public function save_menu($data)
    {
        $id = (int)
        ($data['id'] ?? 0);
        $parentId = (int) ($data['parent_id'] ?? 0);
        $nama = trim($data['nama'] ?? '');
        $slug = trim($data['slug'] ?? '');
        $icon = trim($data['icon'] ?? 'fa-circle');
        $urutan = (int) ($data['urutan'] ?? 0);
        $roles = $data['roles'] ?? array();
        if ($nama === '' || !preg_match('/^[a-z0-9-]+$/', $slug) || !is_array($roles) || !$roles)
            return array('success' => FALSE, 'message' => 'Nama, slug, dan minimal satu peran wajib diisi.');
        $roles = array_values(array_filter($roles, array($this, 'role_exists')));
        if (!$roles) return array('success' => FALSE, 'message' => 'Peran menu tidak valid.');
        $this->db->where('slug', $slug);
        if ($id) $this->db->where('id !=', $id);
        if ($this->db->count_all_results('menus') > 0)
            return array('success' => FALSE, 'message' => 'Slug menu sudah digunakan.');
        // Validasi hanya berlaku saat mengedit menu yang sudah ada
        if ($id > 0 && $parentId > 0 && $parentId === $id) {
            return array(
                'success' => FALSE,
                'message' => 'Menu tidak dapat menjadi induk dirinya sendiri.'
            );
        }
        if ($parentId && !$this->db->where('id', $parentId)->count_all_results('menus'))
            return array('success' => FALSE, 'message' => 'Menu induk tidak ditemukan.');
        $record = array(
            'parent_id' => $parentId ?: NULL,
            'nama' => $nama,
            'slug' => $slug,
            'icon' => $icon,
            'urutan' => $urutan,
            'is_active' => !empty($data['is_active']) ? 1 : 0
        );
        $this->db->trans_start();
        if ($id) $this->db->where('id', $id)->update('menus', $record);
        else {
            $this->db->insert('menus', $record);
            $id = $this->db->insert_id();
        }
        $this->db->where('menu_id', $id)->delete('menu_roles');
        foreach ($roles as $role) $this->db->insert('menu_roles', array('menu_id' => $id, 'role' => $role));
        $this->db->trans_complete();
        return array('success' => $this->db->trans_status(), 'message' => $id ? 'Menu berhasil disimpan.' : 'Menu gagal disimpan.');
    }
    public function delete_menu($id)
    {
        if ($this->db->where('parent_id', (int) $id)->count_all_results('menus')) return FALSE;
        $this->db->where('id', (int) $id)->delete('menus');
        return $this->db->affected_rows() > 0;
    }
    public function get_roles()
    {
        return $this->db->select('id, nama, kode')->order_by('nama')->get('roles')->result_array();
    }
    public function save_role($data)
    {
        $id = (int) ($data['id'] ?? 0);
        $nama = trim($data['nama'] ?? '');
        $kode = trim($data['kode'] ?? '');
        if ($nama === '' || !preg_match('/^[a-z0-9_-]{3,50}$/', $kode)) return array('success' => FALSE, 'message' => 'Nama dan kode peran wajib diisi.');
        $this->db->where('kode', $kode);
        if ($id) $this->db->where('id !=', $id);
        if ($this->db->count_all_results('roles') > 0) return array('success' => FALSE, 'message' => 'Kode peran sudah digunakan.');
        if ($id) {
            $this->db->where('id', $id)->update('roles', array('nama' => $nama, 'kode' => $kode));
            return array('success' => TRUE, 'message' => 'Peran berhasil diperbarui.');
        }
        $this->db->insert('roles', array('nama' => $nama, 'kode' => $kode));
        return array('success' => TRUE, 'message' => 'Peran berhasil ditambahkan.');
    }
    public function delete_role($id)
    {
        $role = $this->db->get_where('roles', array('id' => (int) $id))->row_array();
        if (!$role) return array('success' => FALSE, 'message' => 'Peran tidak ditemukan.');
        if ($role['kode'] === 'admin') return array('success' => FALSE, 'message' => 'Peran administrator utama tidak dapat dihapus.');
        if ($this->db->where('role', $role['kode'])->count_all_results('users') || $this->db->where('role', $role['kode'])->count_all_results('menu_roles')) return array('success' => FALSE, 'message' => 'Peran masih digunakan oleh pegawai atau menu.');
        $this->db->where('id', (int) $id)->delete('roles');
        return array('success' => TRUE, 'message' => 'Peran berhasil dihapus.');
    }
    public function get_users()
    {
        return $this->db->select('id, username, nama_lengkap, role, unit_kerja, is_active, created_at')->order_by('nama_lengkap')->get('users')->result_array();
    }
    public function save_user($data)
    {
        $id = (int) ($data['id'] ?? 0);
        $username = trim($data['username'] ?? '');
        $nama = trim($data['nama_lengkap'] ?? '');
        $unit = trim($data['unit_kerja'] ?? '');
        $role = $data['role'] ?? 'user';
        $password = $data['password'] ?? '';
        if ($username === '' || $nama === '' || $unit === '' || !$this->role_exists($role)) return array('success' => FALSE, 'message' => 'Username, nama, unit kerja, dan peran wajib diisi.');
        if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username)) return array('success' => FALSE, 'message' => 'Format username tidak valid.');
        if (!$id && strlen($password) < 6) return array('success' => FALSE, 'message' => 'Password untuk pegawai baru minimal 6 karakter.');
        $this->db->where('username', $username);
        if ($id) $this->db->where('id !=', $id);
        if ($this->db->count_all_results('users') > 0) return array('success' => FALSE, 'message' => 'Username sudah digunakan.');
        $record = array('username' => $username, 'nama_lengkap' => $nama, 'unit_kerja' => $unit, 'role' => $role);
        if ($password !== '') $record['password'] = password_hash($password, PASSWORD_DEFAULT);
        if ($id) {
            if (!$this->db->where('id', $id)->update('users', $record)) return array('success' => FALSE, 'message' => 'Pegawai gagal diperbarui.');
            return array('success' => TRUE, 'message' => 'Data pegawai berhasil diperbarui.');
        }
        $this->db->insert('users', $record);
        return array('success' => TRUE, 'message' => 'Pegawai berhasil ditambahkan.');
    }
    public function delete_user($id)
    {
        $this->db->where('id', (int) $id)->delete('users');
        return $this->db->affected_rows() > 0;
    }
    private function role_exists($role)
    {
        return $this->db->where('kode', $role)->count_all_results('roles') > 0;
    }
}
