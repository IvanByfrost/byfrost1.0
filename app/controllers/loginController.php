<?php

class LoginController extends mainController
{
    protected $dbConn;
    // Constructor de la clase 
    
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Muestra el formulario de login
    public function index() {}

    // Procesa el formulario y redirige al dashboard según el rol
    public function auth()
    {
        if ($asunto == "login") {
            $credType = $_POST['credType'];
            $documentNum = $_POST['documentNum'];
            $passwordUser = $_POST['password'];

            $query = "SELECT * FROM mainUser WHERE credType = '$credType' and documentNumber = '$documentNum' and userPassword = '$passwordUser' LIMIT 1";
            $stmt = $this->dbConn->prepare($query);
            $stmt->execute([
                'credType' => $credType,
                'documentNum' => $documentNum
            ]);

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($contador1 == 0) {
                $datos = [
                    "estatus"    => "error",
                    "msg"    => "Credenciales Incorrectas",
                ];
                echo json_encode($datos);
                exit;
            } else if ($contador1 >= 1) {
                while ($row1 = mysqli_fetch_array($proceso1)) {
                    $usuarioId = $row1["id"];
                    $rol = $row1["rol"];
                }

                #$redireccion = "admin.php";
                #session_start();
                #$_SESSION["sistemaIvan"] = $usuarioId;
                #$_SESSION["sistemaIvan"] = $rol;

                $datos = [
                    "estatus"    => "ok",
                    "msg" => "Aqui se logea"
                    #"redireccion"	=> $redireccion,
                ];
                echo json_encode($datos);
            }
        }
    }

    // Cierra la sesión y vuelve al login
    public function logout() {}
}
