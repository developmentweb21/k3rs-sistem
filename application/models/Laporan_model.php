<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_model extends CI_Model
{
    public function get_kpi()
    {
        return array('total' => (int) $this->db->count_all('laporan_insiden'), 'near_miss' => (int) $this->db->where('kategori', 'Near Miss')->count_all_results('laporan_insiden'), 'b3' => (int) $this->db->where('kategori', 'Insiden B3')->count_all_results('laporan_insiden'), 'laka_lantas' => (int) $this->db->where('kategori', 'Kecelakaan Lalu Lintas')->count_all_results('laporan_insiden'), 'kepatuhan' => 0);
    }

    public function get_riwayat_insiden($user)
    {
        $this->db->select('laporan_insiden.id, laporan_insiden.tanggal_kejadian, laporan_insiden.kategori, laporan_insiden.lokasi, laporan_insiden.status, laporan_insiden.created_at, users.nama_lengkap AS pelapor');
        $this->db->from('laporan_insiden');
        $this->db->join('users', 'users.id = laporan_insiden.user_id');
        if ($user['role'] !== 'admin') $this->db->where('laporan_insiden.user_id', $user['id']);
        return $this->db->order_by('laporan_insiden.created_at', 'DESC')->get()->result_array();
    }
    public function get_riwayat_kesehatan($user)
    {
        $this->db->select('laporan_kesehatan.id, laporan_kesehatan.nama_karyawan, 
        laporan_kesehatan.unit_kerja, laporan_kesehatan.diagnosa, laporan_kesehatan.hari_tidak_masuk, laporan_kesehatan.created_at, 
        users.nama_lengkap AS pelapor');
        $this->db->from('laporan_kesehatan');
        $this->db->join('users', 'users.id = laporan_insiden.user_id');
        if ($user['role'] !== 'admin') $this->db->where('laporan_insiden.user_id', $user['id']);
        return $this->db->order_by('laporan_insiden.created_at', 'DESC')->get()->result_array();
    }
    public function get_laporan_verifikasi($status = null)
    {
        $this->db->select('
        laporan_insiden.id,
        laporan_insiden.tanggal_kejadian,
        laporan_insiden.kategori,
        laporan_insiden.lokasi,
        laporan_insiden.kronologi,
        laporan_insiden.tindakan_awal,
        laporan_insiden.status,
        laporan_insiden.created_at,
        users.nama_lengkap AS pelapor
    ');

        $this->db->from('laporan_insiden');

        $this->db->join(
            'users',
            'users.id = laporan_insiden.user_id'
        );

        // Filter berdasarkan status jika dipilih
        if (!empty($status)) {
            $this->db->where('laporan_insiden.status', $status);
        }

        $this->db->order_by(
            'laporan_insiden.created_at',
            'DESC'
        );

        return $this->db->get()->result_array();
    }
    public function get_detail_laporan_verifikasi($id)
    {
        return $this->db
            ->select('
            laporan_insiden.id,
            laporan_insiden.tanggal_kejadian,
            laporan_insiden.kategori,
            laporan_insiden.lokasi,
            laporan_insiden.kronologi,
            laporan_insiden.tindakan_awal,
            laporan_insiden.status,
            laporan_insiden.created_at,
            users.nama_lengkap AS pelapor
        ')
            ->from('laporan_insiden')
            ->join('users', 'users.id = laporan_insiden.user_id')
            ->where('laporan_insiden.id', $id)
            ->get()
            ->row_array();
    }
    public function verifikasi_laporan($id)
    {
        $this->db
            ->where('id', $id)
            ->where('status', 'menunggu')
            ->update(
                'laporan_insiden',
                array(
                    'status' => 'diproses'
                )
            );

        return $this->db->affected_rows() > 0;
    }
    public function update_status_laporan(
        $id,
        $status_lama,
        $status_baru
    ) {
        $this->db
            ->where('id', $id)
            ->where('status', $status_lama)
            ->update(
                'laporan_insiden',
                array(
                    'status' => $status_baru
                )
            );

        return $this->db->affected_rows() > 0;
    }
    public function simpan_tindak_lanjut($data)
    {
        return $this->db
            ->insert('laporan_tindak_lanjut', $data);
    }

    public function get_tindak_lanjut_laporan($laporan_id)
    {
        return $this->db
            ->select('
            laporan_tindak_lanjut.*,
            users.nama_lengkap AS verifikator
        ')
            ->from('laporan_tindak_lanjut')
            ->join(
                'users',
                'users.id = laporan_tindak_lanjut.verifikator_id'
            )
            ->where(
                'laporan_tindak_lanjut.laporan_id',
                $laporan_id
            )
            ->order_by(
                'laporan_tindak_lanjut.created_at',
                'DESC'
            )
            ->get()
            ->result_array();
    }
}
