<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function authenticate($username, $password)
    {
        $record = $this->db
            ->select('
            users.id,
            users.nama_lengkap,
            users.password,
            users.role,
            users.unit_kerja,
            roles.nama AS role_nama
        ')
            ->from('users')
            ->join(
                'roles',
                'roles.kode = users.role',
                'left'
            )
            ->where('users.username', $username)
            ->where('users.is_active', 1)
            ->get()
            ->row_array();

        if (!$record || !password_verify($password, $record['password'])) {
            return NULL;
        }

        return array(
            'id'        => (int) $record['id'],
            'nama'      => $record['nama_lengkap'],
            'role'      => $record['role'],
            'role_nama' => $record['role_nama'] ?: $record['role'],
            'unit'      => $record['unit_kerja']
        );
    }
}
