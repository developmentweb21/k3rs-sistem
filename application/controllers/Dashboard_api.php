<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Dashboard_model');
    }


    public function index()
    {
        $user = $this->session->userdata('k3rs_user');

        if (!$user) {
            return $this->json(
                array(
                    'message' => 'Sesi login tidak ditemukan.'
                ),
                401
            );
        }

        $periode = $this->input->get('periode');
        $unit = $this->input->get('unit');

        // Jika user biasa, unit mengikuti unit user
        if (
            isset($user['role']) &&
            $user['role'] === 'user'
        ) {
            $unit = $user['unit'];
        }

        $dashboard = $this->Dashboard_model
            ->get_dashboard($periode, $unit);

        return $this->json(array(
            'filter' => array(
                'periode' => $periode,
                'unit' => $unit
            ),
            'data' => $dashboard
        ));
    }


    private function json($data, $status = 200)
    {
        return $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    public function units()
    {
        $user = $this->session->userdata('k3rs_user');

        if (!$user) {
            return $this->json(
                array(
                    'message' => 'Sesi login tidak ditemukan.'
                ),
                401
            );
        }

        $units = $this->Dashboard_model->get_units();

        return $this->json(array(
            'data' => $units
        ));
    }
}
