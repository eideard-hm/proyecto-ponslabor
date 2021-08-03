<?php

class Menu extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (!$_SESSION['login']) {
            header('Location:' . URL . 'Login');
        }
    }

    //======================== EVIAR Y RECIBIR INFORMACIÓN DEL MODELO =======================

    public function Menu()
    {
        $data['titulo_pagina'] = 'Menú Principal | PonsLabor.';
        $this->views->getView($this, 'Menu', $data);
    }
}
