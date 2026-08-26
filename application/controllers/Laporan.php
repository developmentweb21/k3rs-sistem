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

        $tindak_lanjut = $this->Laporan_model
            ->get_tindak_lanjut_laporan($id);

        // Buat URL lengkap untuk foto dokumentasi
        foreach ($tindak_lanjut as &$item) {
            if (!empty($item['foto'])) {
                $item['foto_url'] = base_url($item['foto']);
            } else {
                $item['foto_url'] = null;
            }
        }

        return $this->json(array(
            'laporan' => $laporan,
            'tindak_lanjut' => $tindak_lanjut
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

        $status_lama = $laporan['status'];

        /*
    |--------------------------------------------------------------------------
    | STATUS MENUNGGU
    |--------------------------------------------------------------------------
    | Wajib mengisi tindak lanjut.
    | Foto bersifat opsional.
    */

        if ($status_lama === 'menunggu') {

            $keterangan = trim(
                $this->input->post('keterangan', true)
            );

            if (empty($keterangan)) {
                return $this->json(
                    array(
                        'message' =>
                        'Keterangan atau tindakan lanjutan wajib diisi.'
                    ),
                    400
                );
            }

            /*
        |--------------------------------------------------------------------------
        | Upload Foto (Opsional)
        |--------------------------------------------------------------------------
        */

            $foto = null;

            if (
                isset($_FILES['foto']) &&
                !empty($_FILES['foto']['name'])
            ) {

                $config['upload_path'] =
                    FCPATH . 'uploads/verifikasi/';

                $config['allowed_types'] =
                    'jpg|jpeg|png';

                $config['max_size'] = 5120; // 5 MB

                $config['encrypt_name'] = TRUE;

                $this->load->library(
                    'upload',
                    $config
                );

                if (
                    !$this->upload->do_upload('foto')
                ) {

                    return $this->json(
                        array(
                            'message' =>
                            strip_tags(
                                $this->upload->display_errors()
                            )
                        ),
                        400
                    );
                }

                $upload_data =
                    $this->upload->data();

                $foto =
                    'uploads/verifikasi/' .
                    $upload_data['file_name'];
            }

            /*
        |--------------------------------------------------------------------------
        | Simpan dengan Transaction
        |--------------------------------------------------------------------------
        */

            $this->db->trans_begin();

            // Simpan catatan tindak lanjut
            $result_tindak_lanjut =
                $this->Laporan_model->simpan_tindak_lanjut(
                    array(
                        'laporan_id' => $id,
                        'verifikator_id' => $user['id'],
                        'keterangan' => $keterangan,
                        'foto' => $foto
                    )
                );

            // Update status
            $result_status =
                $this->Laporan_model->update_status_laporan(
                    $id,
                    'menunggu',
                    'diproses'
                );

            if (
                !$result_tindak_lanjut ||
                !$result_status
            ) {

                $this->db->trans_rollback();

                // Hapus foto jika proses database gagal
                if (
                    $foto &&
                    file_exists(
                        FCPATH . $foto
                    )
                ) {
                    unlink(
                        FCPATH . $foto
                    );
                }

                return $this->json(
                    array(
                        'message' =>
                        'Gagal menyimpan proses verifikasi.'
                    ),
                    500
                );
            }

            $this->db->trans_commit();

            return $this->json(
                array(
                    'message' =>
                    'Verifikasi berhasil disimpan. Laporan sekarang sedang diproses.',
                    'status' => 'diproses'
                )
            );
        }

        /*
    |--------------------------------------------------------------------------
    | STATUS DIPROSES
    |--------------------------------------------------------------------------
    | Selesaikan laporan.
    */

        if ($status_lama === 'diproses') {

            $result =
                $this->Laporan_model->update_status_laporan(
                    $id,
                    'diproses',
                    'selesai'
                );

            if (!$result) {
                return $this->json(
                    array(
                        'message' =>
                        'Gagal menyelesaikan laporan.'
                    ),
                    500
                );
            }

            return $this->json(
                array(
                    'message' =>
                    'Laporan berhasil diselesaikan.',
                    'status' => 'selesai'
                )
            );
        }

        /*
    |--------------------------------------------------------------------------
    | STATUS SELESAI
    |--------------------------------------------------------------------------
    */

        return $this->json(
            array(
                'message' =>
                'Laporan ini sudah selesai dan tidak dapat diproses kembali.'
            ),
            400
        );
    }
    public function detail_tindak_lanjut($id = null)
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

        $item = $this->Laporan_model
            ->get_tindak_lanjut_by_id($id);

        if (!$item) {
            return $this->json(
                array('message' => 'Data tindak lanjut tidak ditemukan.'),
                404
            );
        }

        if (!empty($item['foto'])) {
            $item['foto_url'] = base_url($item['foto']);
        } else {
            $item['foto_url'] = null;
        }

        return $this->json(array(
            'tindak_lanjut' => $item
        ));
    }
    public function update_tindak_lanjut($id = null)
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

        $tindak_lanjut = $this->Laporan_model
            ->get_tindak_lanjut_by_id($id);

        if (!$tindak_lanjut) {
            return $this->json(
                array('message' => 'Data tindak lanjut tidak ditemukan.'),
                404
            );
        }

        $laporan = $this->Laporan_model
            ->get_detail_laporan_verifikasi(
                $tindak_lanjut['laporan_id']
            );

        if (!$laporan) {
            return $this->json(
                array('message' => 'Data laporan tidak ditemukan.'),
                404
            );
        }

        if ($laporan['status'] === 'selesai') {
            return $this->json(
                array(
                    'message' =>
                    'Tindak lanjut tidak dapat diubah karena laporan sudah selesai.'
                ),
                400
            );
        }

        $keterangan = trim(
            $this->input->post('keterangan', true)
        );

        if (empty($keterangan)) {
            return $this->json(
                array('message' => 'Keterangan wajib diisi.'),
                400
            );
        }

        $data_update = array(
            'keterangan' => $keterangan
        );

        $foto_baru = null;
        $foto_lama = $tindak_lanjut['foto'];

        /*
    |--------------------------------------------------------------------------
    | Upload foto baru jika ada
    |--------------------------------------------------------------------------
    */

        if (
            isset($_FILES['foto']) &&
            !empty($_FILES['foto']['name'])
        ) {

            $config['upload_path'] =
                FCPATH . 'uploads/verifikasi/';

            $config['allowed_types'] =
                'jpg|jpeg|png';

            $config['max_size'] = 5120;

            $config['encrypt_name'] = TRUE;

            $this->load->library(
                'upload',
                $config
            );

            if (!$this->upload->do_upload('foto')) {
                return $this->json(
                    array(
                        'message' => strip_tags(
                            $this->upload->display_errors()
                        )
                    ),
                    400
                );
            }

            $upload_data = $this->upload->data();

            $foto_baru =
                'uploads/verifikasi/' .
                $upload_data['file_name'];

            $data_update['foto'] = $foto_baru;
        }

        /*
    |--------------------------------------------------------------------------
    | Update Database
    |--------------------------------------------------------------------------
    */

        $result = $this->Laporan_model
            ->update_tindak_lanjut(
                $id,
                $data_update
            );

        if (!$result) {

            // Hapus foto baru jika database gagal
            if (
                $foto_baru &&
                file_exists(FCPATH . $foto_baru)
            ) {
                unlink(FCPATH . $foto_baru);
            }

            return $this->json(
                array(
                    'message' =>
                    'Gagal memperbarui tindak lanjut.'
                ),
                500
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Hapus foto lama setelah update berhasil
    |--------------------------------------------------------------------------
    */

        if (
            $foto_baru &&
            $foto_lama &&
            file_exists(FCPATH . $foto_lama)
        ) {
            unlink(FCPATH . $foto_lama);
        }

        return $this->json(
            array(
                'message' =>
                'Tindak lanjut berhasil diperbarui.'
            )
        );
    }
}
