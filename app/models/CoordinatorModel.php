<?php
class CoordinatorModel extends MainModel {
    protected $dbConn;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    //Función para obtener los datos del coordinador
    public function getCoordinator(){
        // Aquí podrías realizar una consulta a la base de datos para obtener los datos del coordinador
    }
    //Función para crear un coordinador
    public function createCoordinator(){
        // Aquí podrías realizar una inserción en la base de datos para crear un nuevo coordinador
    }
    //Función para actualizar un coordinador
    public function updateCoordinator(){
        // Aquí podrías realizar una actualización en la base de datos para modificar los datos de un coordinador existente
    }
    //Función para eliminar un coordinador
    

    public function getData() {
        return [
            'nombre' => 'María Gómez',
            'rol' => 'Coordinadora',
            'estudiante' => [
                'nombre' => 'Camilo Rodríguez',
                'estado' => 'En mora',
                'fecha_pago' => '05 de abril de 2025',
                'matricula' => 5700000,
                'pension' => 290000
            ],
            'horario' => [
                ['materia' => 'Biología', 'docente' => 'Camilo Fernández', 'aula' => '201'],
                ['materia' => 'Inglés', 'docente' => 'Christian Torres', 'aula' => '301'],
                ['materia' => 'Tecnología', 'docente' => 'Laura Montesco', 'aula' => 'Sala de Tecnología 1']
            ],
            'citaciones' => [
                ['fecha' => '10 de abril', 'motivo' => 'Bajo rendimiento del estudiante'],
                ['fecha' => '11 de mayo', 'motivo' => 'Conversar sobre el rendimiento del estudiante']
            ]
        ];
    }
}
