<?php

class Login extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        // //isset : verifica que la varible de sesion si exista
        if (isset($_SESSION['login'])) {
            if ($_SESSION['user-data']['nombreRol'] === 'Contratante') {
                header('Location: ' . URL . 'Menu/Menu_Contratante');
            } else {
                header('Location:' . URL . 'Menu');
            }
        }
    }

    //======================== EVIAR Y RECIBIR INFORMACIÓN DEL MODELO =======================

    public function Login()
    {
        $data['titulo_pagina'] = 'Iniciar Sesión | PonsLabor.';
        $this->views->getView($this, 'Login', $data);
    }

    public function Recuperar_Password()
    {
        $data['titulo_pagina'] = 'Recuperar Contraseña | PonsLabor.';
        $this->views->getView($this, 'Recuperar_Password', $data);
    }

    public function Correo_Recuperar_Password()
    {
        $data['titulo_pagina'] = 'Recuperar Contraseña | PonsLabor.';
        $this->views->getView($this, 'Correo_Recuperar_Password', $data);
    }

    public function loginUser()
    {
        if ($_POST) {
            if (empty($_POST['Usuario']) || empty($_POST['Password'])) {
                $arrResponse = array('statusLogin' => false, 'msg' => 'Todos los campos son obligatorios.');
            } else {
                $Usuario = strtolower(limpiarCadena($_POST['Usuario'])); //convertir todas las letras en minusculas
                $Password = limpiarCadena($_POST['Password']);

                $request = $this->model->validatePassword($Usuario, $Password);

                if ($request) {
                    $arrData = $this->model->loginUser($Usuario, $Password);
                    if (!empty($arrData)) {
                        if ($arrData['estadoUsuario'] == 0) {
                            $_SESSION['id'] = $arrData['idUsuario'];
                            $_SESSION['login'] = true;
                            $_SESSION['user-data'] = $arrData;

                            //petición para cargar la imagen del usuario
                            $imgProfile = $this->model->selectImgProfile($_SESSION['id']);
                            $_SESSION['imgProfile'] = URL . "Assets/img/uploads/{$imgProfile['imagenUsuario']}";

                            $arrResponse = array('statusLogin' => true, 'msg' => 'ok', 'rol' => $_SESSION['user-data']['nombreRol']);
                        } else {
                            $arrResponse = array('statusLogin' => false, 'msg' => 'El usuario se encuentra inhabilitado!.');
                        }
                    } else {
                        $arrResponse = array('statusLogin' => false, 'msg' => 'El usuario no se encuentra registrado. Puedes crear una cuenta es gratis!!', 'data' => $arrData);
                    }
                } elseif ($request === 'error' || $request === false) {
                    $arrResponse = array('statusLogin' => false, 'msg' => 'El email o la contraseña ingresados son incorrectos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    /*-------------------------RECUPERAR CONTRASEÑA -----------------*/ 
   /* public function resetPass(){
        if($_POST){
            error_reporting(0);
    
            if(empty($_POST['txtEmailReset'])){
                $arrResponse = array('status' => false, 'msg' => 'Error de datos' );
            }else{
                $token = token();
                $strEmail  =  strtolower(strClean($_POST['txtEmailReset']));
                $arrData = $this->model->getUserEmail($strEmail);
    
                if(empty($arrData)){
                    $arrResponse = array('status' => false, 'msg' => 'Usuario no existente.' ); 
                }else{
                    $idpersona = $arrData['idpersona'];
                    $nombreUsuario = $arrData['nombres'].' '.$arrData['apellidos'];
    
                    $url_recovery = base_url().'/login/confirmUser/'.$strEmail.'/'.$token;
                    $requestUpdate = $this->model->setTokenUser($idpersona,$token);
    
                    $dataUsuario = array('nombreUsuario' => $nombreUsuario,
                                         'email' => $strEmail,
                                         'asunto' => 'Recuperar cuenta - '.NOMBRE_REMITENTE,
                                         'url_recovery' => $url_recovery);
                    if($requestUpdate){
                        $sendEmail = sendEmail($dataUsuario,'email_cambioPassword');
    
                        if($sendEmail){
                            $arrResponse = array('status' => true, 
                                             'msg' => 'Se ha enviado un email a tu cuenta de correo para cambiar tu contraseña.');
                        }else{
                            $arrResponse = array('status' => false, 
                                             'msg' => 'No es posible realizar el proceso, intenta más tarde.' );
                        }
                    }else{
                        $arrResponse = array('status' => false, 
                                             'msg' => 'No es posible realizar el proceso, intenta más tarde.' );
                    }
                }
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
        die();
    }*/
}

 
