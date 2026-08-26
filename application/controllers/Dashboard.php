<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Master_model');
        $this->load->model('Laporan_model');
    }

    public function index()
    {
        $user = $this->session->userdata('k3rs_user');
        if (!$user) redirect('login');

        $data = array(
            'title' => 'Sistem Pelaporan K3RS',
            'bootstrap' => array(
                'user' => $user,
                'kategori' => $this->Master_model->get_nama_list('master_kategori'),
                'unit' => $this->Master_model->get_nama_list('master_unit'),
                'checklist' => $this->Master_model->get_nama_list('master_checklist'),
                'kpi' => $this->Laporan_model->get_kpi(),
            ),
        );

        $this->load->view('layouts/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('layouts/footer');
    }
}
