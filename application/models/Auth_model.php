<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function authenticate($username, $password)
    {
        $record = $this->db->get_where('users', array('username' => $username, 'is_active' => 1))->row_array();
        if (!$record || !password_verify($password, $record['password'])) return NULL;
        return array('id' => (int) $record['id'], 'nama' => $record['nama_lengkap'], 'role' => $record['role'], 'unit' => $record['unit_kerja']);
    }
}
