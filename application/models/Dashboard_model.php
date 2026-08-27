<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function get_dashboard($periode = null, $unit = null)
    {
        return array(
            'insiden' => $this->get_insiden($periode, $unit),
            'kesehatan' => $this->get_kesehatan($periode, $unit),
            'checklist' => $this->get_checklist($periode, $unit)
        );
    }


    private function get_insiden($periode = null, $unit = null)
    {
        $this->db->select('
            COUNT(laporan_insiden.id) AS total,

            SUM(
                CASE
                    WHEN laporan_insiden.status = "menunggu"
                    THEN 1
                    ELSE 0
                END
            ) AS menunggu,

            SUM(
                CASE
                    WHEN laporan_insiden.status = "proses"
                    THEN 1
                    ELSE 0
                END
            ) AS proses,

            SUM(
                CASE
                    WHEN laporan_insiden.status = "selesai"
                    THEN 1
                    ELSE 0
                END
            ) AS selesai
        ', FALSE);

        $this->db->from('laporan_insiden');

        // Untuk filter unit berdasarkan unit pelapor
        $this->db->join(
            'users',
            'users.id = laporan_insiden.user_id',
            'left'
        );

        if ($periode) {
            $this->db->where(
                'DATE_FORMAT(laporan_insiden.tanggal_kejadian, "%Y-%m") =',
                $periode
            );
        }

        if ($unit) {
            $this->db->where(
                'users.unit_kerja',
                $unit
            );
        }

        $result = $this->db->get()->row_array();

        return array(
            'total' => (int) ($result['total'] ?? 0),
            'menunggu' => (int) ($result['menunggu'] ?? 0),
            'proses' => (int) ($result['proses'] ?? 0),
            'selesai' => (int) ($result['selesai'] ?? 0)
        );
    }


    private function get_kesehatan($periode = null, $unit = null)
    {
        $this->db->select(
            'COUNT(id) AS total',
            FALSE
        );

        $this->db->from('laporan_kesehatan');

        if ($periode) {
            $this->db->where(
                'DATE_FORMAT(created_at, "%Y-%m") =',
                $periode
            );
        }

        if ($unit) {
            $this->db->where(
                'unit_kerja',
                $unit
            );
        }

        $result = $this->db->get()->row_array();

        return array(
            'total' => (int) ($result['total'] ?? 0)
        );
    }


    private function get_checklist($periode = null, $unit = null)
    {
        $this->db->select('
            COUNT(id) AS total_laporan,
            COALESCE(SUM(total_item), 0) AS total_item,
            COALESCE(SUM(jumlah_sesuai), 0) AS sesuai
        ', FALSE);

        $this->db->from('laporan_checklist');

        if ($periode) {
            $this->db->where(
                'periode',
                $periode
            );
        }

        if ($unit) {
            $this->db->where(
                'unit_kerja',
                $unit
            );
        }

        $result = $this->db->get()->row_array();

        $totalItem = (int) ($result['total_item'] ?? 0);
        $sesuai = (int) ($result['sesuai'] ?? 0);

        $tidakSesuai = $totalItem - $sesuai;

        $kepatuhan = $totalItem > 0
            ? round(($sesuai / $totalItem) * 100, 2)
            : 0;

        return array(
            'total_laporan' => (int) ($result['total_laporan'] ?? 0),
            'total_item' => $totalItem,
            'sesuai' => $sesuai,
            'tidak_sesuai' => $tidakSesuai,
            'kepatuhan' => $kepatuhan
        );
    }
    public function get_units()
    {
        return $this->db
            ->select('id, nama')
            ->from('master_unit')
            ->order_by('nama', 'ASC')
            ->get()
            ->result_array();
    }
}
