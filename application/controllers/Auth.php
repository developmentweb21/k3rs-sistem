<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function index()
    {
        if ($this->session->userdata('k3rs_user')) redirect('dashboard');

        $data = array('title' => 'Masuk - Sistem Pelaporan K3RS');
        $this->load->view('layouts/header', $data);
        $this->load->view('auth/login');
        $this->load->view('layouts/footer', $data);
    }
}
