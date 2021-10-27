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
        $data['titulo_pagina'] = 'Iniciar Sesión | ' . NOMBRE_EMPRESA . '.';
        $this->views->getView($this, 'Login', $data);
    }

    public function Recuperar_Password(string $params)
    {
        $arrParams = explode('/', $_GET['url']);
        if (!isset($arrParams[2])) {
            header('Location:' . base_url());
        } else {
            // $arrParams = explode(',', $params);
            $strEmail = limpiarCadena($arrParams[2]);
            $strEmail = str_replace('.', '', $strEmail);
            $strEmail = str_replace('@', '-', $strEmail);
            var_dump($strEmail);
            $strToken =  limpiarCadena($arrParams[3]);

            $arrResponse = $this->model->getUsuario($strEmail, $strToken);
            if (empty($arrResponse)) {
                header("Location:" . base_url());
            } else {
                $data['titulo_pagina'] = 'Recuperar Contraseña | ' . NOMBRE_EMPRESA . '.';
                $data['idUsuario'] = $arrResponse['idUsuario'];
                $data['pege_funtion_js']=["login.js"];
                $this->views->getView($this, 'Recuperar_Password', $data);
            }
        }
        die();
    }
    public function setPassword()
    {

        if (empty($_POST['idUsuario']) || empty($_POST['txtEmail']) || empty($_POST['txtToken']) || empty($_POST['txtPassword']) || empty($_POST['txtPasswordConfirm'])) {

            $arrResponse = array(
                'status' => false,
                'msg' => 'Error de datos'
            );
        } else {
            $intIdpersona = intval($_POST['idUsuario']);
            $strPassword = $_POST['txtPassword'];
            $strPasswordConfirm = $_POST['txtPasswordConfirm'];
            $strEmail = limpiarCadena($_POST['txtEmail']);
            $strToken = limpiarCadena($_POST['txtToken']);

            if ($strPassword != $strPasswordConfirm) {
                $arrResponse = array(
                    'status' => false,
                    'msg' => 'Las contraseñas no son iguales.'
                );
            } else {
                $arrResponseUser = $this->model->getUsuario($strEmail, $strToken);
                if (empty($arrResponseUser)) {
                    $arrResponse = array(
                        'status' => false,
                        'msg' => 'Erro de datos.'
                    );
                } else {
                    $strPassword = hash("SHA256", $strPassword);
                    $requestPass = $this->model->insertPassword($intIdpersona, $strPassword);

                    if ($requestPass) {
                        $arrResponse = array(
                            'status' => true,
                            'msg' => 'Contraseña actualizada con éxito.'
                        );
                    } else {
                        $arrResponse = array(
                            'status' => false,
                            'msg' => 'No es posible realizar el proceso, intente más tarde.'
                        );
                    }
                }
            }
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function Correo_Recuperar_Password()
    {
        $data['titulo_pagina'] = 'Recuperar Contraseña | ' . NOMBRE_EMPRESA . '.';
        $this->views->getView($this, 'Correo_Recuperar_Password', $data);
    }

    public function loginUser()
    {
        //validamos que los campos no esten vacios retornando un mensaje
        if ($_POST) {
            if (empty($_POST['Usuario']) || empty($_POST['Password'])) {
                $arrResponse = array('statusLogin' => false, 'msg' => 'Todos los campos son obligatorios.');
            }
            //de no estar vacios creamos las variables para almacenar los datos que estamos resiviendo 
            else {
                $Usuario = strtolower(limpiarCadena($_POST['Usuario'])); //funcion que converte todas las letras en minusculas
                $Password = limpiarCadena($_POST['Password']);
                //esto es iguaal a  lo que nos va a retornar el metodo  que vamos a tener en el modelo y mandamos como parametro el usuario y el Password
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

    public function resetPass()
    {
        if ($_POST) {
            if (empty($_POST['txtEmailReset'])) {
                $arrResponse = array('status' => false, "msg" => 'Error de datos');
            } else {
                $token = token();
                $strEmail = strtolower(limpiarCadena($_POST['txtEmailReset']));
                $arrData = $this->model->getUserEmail($strEmail);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, "msg" => 'Usuario no existente');
                } else {
                    $idUsuario = $arrData['idUsuario'];
                    $nombreUsuario = $arrData['nombreUsuario'];
                    $url_recovery = base_url() . 'login/confirUser/' . $strEmail . '/' . $token;

                    $requestUpdate = $this->model->setTokenUser($idUsuario, $token);
                    if ($requestUpdate) {
                        $arrResponse = array(
                            'status' => true,
                            'msg' => 'Se ha envido un email a su cuenta de correo
                                             para cambiar su contraseña.'
                        );
                    } else {
                        $arrResponse = array(
                            'status' => false,
                            'msg' => 'No es posible realizar el proceso, intente 
                                            mas tarde.'
                        );
                    }
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
