<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\userM;

class Login extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new userM();
        helper('form');
    }
    public function index()
    {
        return redirect()->to(site_url('\login'));
    }

    public function login()
    {

        $data = [
            'title' => 'LATSAR | Login',
            'login' => \Config\Services::validation(),
        ];

        if (session('idUser')) {

            return redirect()->to(site_url('/home'));
        }

        echo view('Auth/login', $data);
    }

    public function loginProcess()
    {
        // echo "Lanjut gan!";
        $post = $this->request->getPost();

        $query = $this->userModel->table('user')->getWhere(['email' => $post['email']]);
        $user = $query->getRow();
        if ($user) {
            if (password_verify($post['password'], $user->password)) {
                $id = ['idUser' => $user->idUser];
                $level = ['level' => $user->level];
                session()->set($id);
                session()->set($level);
                if (session()->get('level') != 1) {
                    return redirect()->to('/mendatapgw');
                }
                return redirect()->to('/home');
            } else {
                return redirect()->back()->with('error', 'Password tidak sesuai');
            }
        } else {
            return redirect()->back()->with('error', 'Email tidak ditemukan');
        }
        //return redirect()->to(site_url(''));
    }

    public function logout()
    {
        session()->remove('idUser');
        return redirect()->to(site_url('/signin'));
    }
}
