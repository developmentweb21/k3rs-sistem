<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
    }

    public function simpan($jenis)
    {
        $user = $this->session->userdata('k3rs_user');
        if (!$user) return $this->json(array('message' => 'Sesi login tidak ditemukan.'), 401);
        $payload = json_decode($this->input->raw_input_stream, TRUE) ?: array();
        if (!$this->Transaksi_model->save($jenis, $payload, $user))
            return $this->json(array('message' => 'Jenis laporan tidak valid.'), 422);
        return $this->json(array('message' => 'Data berhasil disimpan'));
    }

    public function karyawan_unit()
    {
        $user = $this->session->userdata('k3rs_user');
        if (!$user) return $this->json(array('message' => 'Sesi login tidak ditemukan.'), 401);

        return $this->json(array(
            'karyawan' => $this->Transaksi_model->get_karyawan_by_unit($user['unit'])
        ));
    }

    private function json($data, $status = 200)
    {
        return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode($data));
    }
}
