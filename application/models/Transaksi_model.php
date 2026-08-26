<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model
{
    public function save($jenis, $data, $user)
    {
        if ($jenis === 'insiden') return $this->db->insert('laporan_insiden', array('user_id' => $user['id'], 'kategori' => trim($data['kategori'] ?? ''), 'tanggal_kejadian' => $data['tanggal'] ?? NULL, 'lokasi' => trim($data['lokasi'] ?? ''), 'kronologi' => trim($data['kronologi'] ?? ''), 'tindakan_awal' => trim($data['tindakan'] ?? '')));
        if ($jenis === 'kesehatan') return $this->db->insert('laporan_kesehatan', array('user_id' => $user['id'], 'nama_karyawan' => trim($data['nama'] ?? ''), 'unit_kerja' => $user['unit'], 'diagnosa' => trim($data['diagnosa'] ?? ''), 'hari_tidak_masuk' => (int) ($data['hari'] ?? 0)));
        if ($jenis === 'checklist') return $this->db->insert('laporan_checklist', array('user_id' => $user['id'], 'periode' => $data['periode'] ?? NULL, 'tanggal_pengisian' => $data['tanggal'] ?? NULL, 'unit_kerja' => trim($data['unit'] ?? ''), 'jumlah_sesuai' => count($data['items'] ?? array()), 'total_item' => $this->db->count_all('master_checklist')));
        return FALSE;
    }
}
