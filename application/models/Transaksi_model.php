<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_model extends CI_Model
{
    public function save($jenis, $data, $user)
    {
        if ($jenis === 'insiden') return $this->db->insert(
            'laporan_insiden',
            array(
                'user_id' => $user['id'],
                'kategori' => trim($data['kategori'] ?? ''),
                'tanggal_kejadian' => $data['tanggal'] ?? NULL,
                'lokasi' => trim($data['lokasi'] ?? ''),
                'kronologi' => trim($data['kronologi'] ?? ''),
                'tindakan_awal' => trim($data['tindakan'] ?? '')
            )
        );
        if ($jenis === 'kesehatan') return $this->db->insert(
            'laporan_kesehatan',
            array(
                'user_id' => $user['id'],
                'nama_karyawan' => trim($data['nama'] ?? ''),
                'unit_kerja' => $user['unit'],
                'diagnosa' => trim($data['diagnosa'] ?? ''),
                'hari_tidak_masuk' => (int) ($data['hari'] ?? 0)
            )
        );
        if ($jenis === 'checklist') {

            $items = isset($data['items']) && is_array($data['items'])
                ? $data['items']
                : array();

            if (empty($items)) {
                return FALSE;
            }

            $jumlah_sesuai = 0;

            foreach ($items as $item) {
                if (
                    isset($item['jawaban']) &&
                    strtolower($item['jawaban']) === 'yes'
                ) {
                    $jumlah_sesuai++;
                }
            }

            $this->db->trans_start();

            // Simpan header laporan
            $this->db->insert(
                'laporan_checklist',
                array(
                    'user_id' => $user['id'],
                    'periode' => $data['periode'] ?? NULL,
                    'tanggal_pengisian' => $data['tanggal'] ?? NULL,
                    'unit_kerja' => trim($data['unit'] ?? ''),
                    'jumlah_sesuai' => $jumlah_sesuai,
                    'total_item' => count($items)
                )
            );

            $laporan_id = $this->db->insert_id();

            // Simpan jawaban setiap checklist
            foreach ($items as $item) {

                if (
                    empty($item['checklist_id']) ||
                    !isset($item['jawaban'])
                ) {
                    continue;
                }

                $jawaban = strtolower($item['jawaban']);

                if (!in_array($jawaban, array('yes', 'no'))) {
                    continue;
                }

                $this->db->insert(
                    'laporan_checklist_detail',
                    array(
                        'laporan_checklist_id' => $laporan_id,
                        'checklist_id' => $item['checklist_id'],
                        'jawaban' => $jawaban
                    )
                );
            }

            $this->db->trans_complete();

            return $this->db->trans_status();
        }
        return FALSE;
    }
}
