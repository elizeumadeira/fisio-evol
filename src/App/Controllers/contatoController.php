<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validate;
use App\Core\Email;
class contatoController extends Controller
{
    public function index($request, $response)
    {
        $this->view('contato', ['title' => 'Contato', 'dados' => 'Fale Conosco']);
    }

    public function store($request, $response, $args)
    {
        $validate = new Validate;
        $data = $validate->validate([
            'email' => 'required',
            'nome' => 'required',
            'mensagem' => 'required',
        ]);

        if ($validate->hasErrors()) {
            return $this->create();
        }
        $email = new Email;
        $email->data([
            'fromName'=> $data->nome,
            'fromEmail'=> $data->email,
            'toName' => 'Samuel Mattos',
            'toEmail' => 'muka@outlook.com',
            'mensagem' => $data->mensagem,
        ])->send();
    }
}