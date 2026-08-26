<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
    private function json($data, $status = 200)
    {
        return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode($data));
    }
    public function verifikasi()
    {
        $user = $this->session->userdata('k3rs_user');

        if (!$user) {
            return $this->json(
                array('message' => 'Sesi login tidak ditemukan.'),
                401
            );
        }

        if ($user['role'] !== 'admin') {
            return $this->json(
                array('message' => 'Akses ditolak.'),
                403
            );
        }

        $status = $this->input->get('status', true);

        // Validasi status
        $allowed_status = array(
            'menunggu',
            'diproses',
            'selesai'
        );

        if (!empty($status) && !in_array($status, $allowed_status)) {
            return $this->json(
                array('message' => 'Status tidak valid.'),
                400
            );
        }

        $laporan = $this->Laporan_model
            ->get_laporan_verifikasi($status);

        return $this->json(array(
            'laporan' => $laporan
        ));
    }
    public function detail_verifikasi($id = null)
    {
        $user = $this->session->userdata('k3rs_user');

        if (!$user) {
            return $this->json(
                array('message' => 'Sesi login tidak ditemukan.'),
                401
            );
        }

        if ($user['role'] !== 'admin') {
            return $this->json(
                array('message' => 'Akses ditolak.'),
                403
            );
        }

        if (!$id) {
            return $this->json(
                array('message' => 'ID laporan tidak ditemukan.'),
                400
            );
        }

        $laporan = $this->Laporan_model
            ->get_detail_laporan_verifikasi($id);

        if (!$laporan) {
            return $this->json(
                array('message' => 'Data laporan tidak ditemukan.'),
                404
            );
        }

        return $this->json(array(
            'laporan' => $laporan
        ));
    }
    public function proses_verifikasi($id = null)
    {
        $user = $this->session->userdata('k3rs_user');

        if (!$user) {
            return $this->json(
                array('message' => 'Sesi login tidak ditemukan.'),
                401
            );
        }

        if ($user['role'] !== 'admin') {
            return $this->json(
                array('message' => 'Akses ditolak.'),
                403
            );
        }

        if (!$id) {
            return $this->json(
                array('message' => 'ID laporan tidak ditemukan.'),
                400
            );
        }

        $laporan = $this->Laporan_model
            ->get_detail_laporan_verifikasi($id);

        if (!$laporan) {
            return $this->json(
                array('message' => 'Data laporan tidak ditemukan.'),
                404
            );
        }

        if ($laporan['status'] !== 'menunggu') {
            return $this->json(
                array(
                    'message' => 'Laporan ini sudah tidak dalam status menunggu.'
                ),
                400
            );
        }

        $result = $this->Laporan_model
            ->verifikasi_laporan($id);

        if (!$result) {
            return $this->json(
                array(
                    'message' => 'Status laporan gagal diperbarui.'
                ),
                500
            );
        }

        return $this->json(array(
            'message' => 'Laporan berhasil diverifikasi dan sedang diproses.',
            'status'  => 'diproses'
        ));
    }
}
