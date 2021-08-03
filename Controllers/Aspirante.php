<?php

class Aspirante extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        // //isset : verifica que la varible de sesion si exista
        if (!isset($_SESSION['login'])) {
            header('Location: http://localhost/PonsLabor/Login');
        }
    }

    //======================== EVIAR Y RECIBIR INFORMACIÓN DEL MODELO =======================

    public function Aspirante()
    {
        $data['titulo_pagina'] = 'Aspirante | PonsLabor.';
        $this->views->getView($this, 'Aspirante', $data);
    }
}
