<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    public function login()
    {
        $payload = json_decode($this->input->raw_input_stream, TRUE);
        $user = $this->Auth_model->authenticate($payload['username'] ?? '', $payload['password'] ?? '');
        if (!$user) {
            return $this->json(array('message' => 'ID atau password salah.'), 401);
        }

        $this->session->set_userdata('k3rs_user', $user);
        return $this->json(array('user' => $user));
    }

    public function logout()
    {
        $this->session->unset_userdata('k3rs_user');
        return $this->json(array('message' => 'Anda telah keluar.'));
    }

    private function json($data, $status = 200)
    {
        return $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode($data));
    }
}
