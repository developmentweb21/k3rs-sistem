<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Master_model');
        $this->load->model('Laporan_model');
    }

    public function data_awal()
    {
        $user = $this->session->userdata('k3rs_user');
        if (!$user) return $this->json(array('message' => 'Sesi login tidak ditemukan.'), 401);
        return $this->json(array('user' => $user, 'kategori' => $this->Master_model->get_nama_list('master_kategori'), 'unit' => $this->Master_model->get_nama_list('master_unit'), 'checklist' => $this->Master_model->get_nama_list('master_checklist'), 'kpi' => $this->Laporan_model->get_kpi()));
    }

    public function riwayat()
    {
        $user = $this->session->userdata('k3rs_user');
        if (!$user) return $this->json(array('message' => 'Sesi login tidak ditemukan.'), 401);
        return $this->json(array('laporan' => $this->Laporan_model->get_riwayat_insiden($user)));
    }
    private function json($data, $status = 200) { return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode($data)); }
}
