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
    public function get_laporan_verifikasi()
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
            ->join(
                'users',
                'users.id = laporan_insiden.user_id'
            )
            ->where('laporan_insiden.status', 'menunggu')
            ->order_by('laporan_insiden.created_at', 'DESC')
            ->get()
            ->result_array();
    }
}
