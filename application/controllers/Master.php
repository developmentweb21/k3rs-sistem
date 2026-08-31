<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Master_model');
    }

    public function users()
    {
        if (!$this->require_admin()) return;
        if ($this->input->method(TRUE) === 'GET') return $this->json(array('users' => $this->Master_model->get_users()));

        $result = $this->Master_model->save_user($this->payload());
        return $this->json($result, $result['success'] ? 200 : 422);
    }

    public function hapus_user($id)
    {
        $admin = $this->require_admin();
        if (!$admin) return;
        if ((int) $admin['id'] === (int) $id) return $this->json(array('message' => 'Akun yang sedang digunakan tidak dapat dihapus.'), 422);
        $success = $this->Master_model->delete_user($id);
        return $this->json(array('message' => $success ? 'Pegawai berhasil dihapus.' : 'Pegawai tidak ditemukan.'), $success ? 200 : 404);
    }

    public function data($jenis)
    {
        if (!$this->require_admin()) return;
        if (!$this->Master_model->table_master($jenis)) return $this->json(array('message' => 'Jenis master tidak valid.'), 404);
        if ($this->input->method(TRUE) === 'GET') return $this->json(array('data' => $this->Master_model->get_master($jenis)));

        $result = $this->Master_model->save_master($jenis, $this->payload());
        return $this->json($result, $result['success'] ? 200 : 422);
    }

    public function hapus_data($jenis, $id)
    {
        if (!$this->require_admin()) return;
        $success = $this->Master_model->delete_master($jenis, $id);
        return $this->json(array('message' => $success ? 'Data master berhasil dihapus.' : 'Data master tidak ditemukan.'), $success ? 200 : 404);
    }

    public function navigasi()
    {
        $user = $this->session->userdata('k3rs_user');
        if (!$user) return $this->json(array('message' => 'Sesi login tidak ditemukan.'), 401);
        return $this->json(array('menus' => $this->Master_model->get_navigation($user['role'])));
    }

    public function menu()
    {
        if (!$this->require_admin()) return;
        if ($this->input->method(TRUE) === 'GET') return $this->json(array('menus' => $this->Master_model->get_menus()));
        $result = $this->Master_model->save_menu($this->payload());
        return $this->json($result, $result['success'] ? 200 : 422);
    }

    public function hapus_menu($id)
    {
        if (!$this->require_admin()) return;
        $success = $this->Master_model->delete_menu($id);
        return $this->json(array('message' => $success ? 'Menu berhasil dihapus.' : 'Menu tidak ditemukan.'), $success ? 200 : 404);
    }

    public function settings()
    {
        if (!$this->require_admin()) return;
        if ($this->input->method(TRUE) === 'GET') return $this->json(array('settings' => $this->Master_model->get_app_settings()));
        $result = $this->Master_model->save_app_settings($this->payload());
        return $this->json($result, $result['success'] ? 200 : 422);
    }

    public function roles()
    {
        if (!$this->require_admin()) return;
        if ($this->input->method(TRUE) === 'GET') return $this->json(array('roles' => $this->Master_model->get_roles()));
        $result = $this->Master_model->save_role($this->payload());
        return $this->json($result, $result['success'] ? 200 : 422);
    }

    public function hapus_role($id)
    {
        if (!$this->require_admin()) return;
        $result = $this->Master_model->delete_role($id);
        return $this->json($result, $result['success'] ? 200 : 422);
    }

    private function require_admin()
    {
        $user = $this->session->userdata('k3rs_user');
        if (!$user || $user['role'] !== 'admin') { $this->json(array('message' => 'Akses ini hanya untuk administrator.'), 403); return NULL; }
        return $user;
    }
    private function payload() { return json_decode($this->input->raw_input_stream, TRUE) ?: array(); }
    private function json($data, $status = 200) { return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode($data)); }
}
