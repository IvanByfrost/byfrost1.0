<?php
/**
 * Test Form Validations - ByFrost
 * Prueba automática de validaciones y CSRF en formularios y flujos principales
 */

require_once __DIR__ . '/../library/Validator.php';

class FormValidationTester {
    private $results = [];
    private $csrfToken = '';

    public function __construct() {
        echo "🧪 Iniciando pruebas automáticas de validaciones y CSRF...\n\n";
        session_start();
        $this->csrfToken = Validator::generateCSRFToken();
        $this->testLogin();
        $this->testRegister();
        $this->testChangePassword();
        $this->testCreateUser();
        $this->testCreateSchool();
        $this->testAddGrade();
        $this->generateReport();
    }

    private function testLogin() {
        echo "🔐 Probando Login...\n";
        // Caso válido
        $_POST = [
            'email' => 'test@example.com',
            'password' => 'Test1234',
            'csrf_token' => $this->csrfToken
        ];
        ob_start();
        include __DIR__ . '/../controllers/LoginController.php';
        $output = ob_get_clean();
        $this->results['login_valid'] = strpos($output, 'error') === false;

        // Caso inválido: email vacío
        $_POST = [
            'email' => '',
            'password' => 'Test1234',
            'csrf_token' => $this->csrfToken
        ];
        ob_start();
        include __DIR__ . '/../controllers/LoginController.php';
        $output = ob_get_clean();
        $this->results['login_email_empty'] = strpos($output, 'error') !== false;

        // Caso inválido: token CSRF incorrecto
        $_POST = [
            'email' => 'test@example.com',
            'password' => 'Test1234',
            'csrf_token' => 'invalidtoken'
        ];
        ob_start();
        include __DIR__ . '/../controllers/LoginController.php';
        $output = ob_get_clean();
        $this->results['login_csrf_invalid'] = strpos($output, 'error') !== false;
    }

    private function testRegister() {
        echo "📝 Probando Registro...\n";
        $_POST = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Test1234',
            'confirm_password' => 'Test1234',
            'document' => '12345678',
            'csrf_token' => $this->csrfToken
        ];
        ob_start();
        include __DIR__ . '/../controllers/RegisterController.php';
        $output = ob_get_clean();
        $this->results['register_valid'] = strpos($output, 'error') === false;

        // Caso inválido: email mal formado
        $_POST['email'] = 'bademail';
        ob_start();
        include __DIR__ . '/../controllers/RegisterController.php';
        $output = ob_get_clean();
        $this->results['register_email_invalid'] = strpos($output, 'error') !== false;
    }

    private function testChangePassword() {
        echo "🔑 Probando Cambio de Contraseña...\n";
        $_POST = [
            'current_password' => 'OldPass123',
            'new_password' => 'NewPass123',
            'confirm_password' => 'NewPass123',
            'csrf_token' => $this->csrfToken
        ];
        ob_start();
        include __DIR__ . '/../controllers/ForgotPasswordController.php';
        $output = ob_get_clean();
        $this->results['change_password_valid'] = strpos($output, 'error') === false;

        // Caso inválido: contraseñas no coinciden
        $_POST['confirm_password'] = 'OtherPass';
        ob_start();
        include __DIR__ . '/../controllers/ForgotPasswordController.php';
        $output = ob_get_clean();
        $this->results['change_password_mismatch'] = strpos($output, 'error') !== false;
    }

    private function testCreateUser() {
        echo "👥 Probando Creación de Usuario...\n";
        $_POST = [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@ejemplo.com',
            'role' => 'admin',
            'csrf_token' => $this->csrfToken
        ];
        ob_start();
        include __DIR__ . '/../controllers/UserController.php';
        $output = ob_get_clean();
        $this->results['create_user_valid'] = strpos($output, 'error') === false;

        // Caso inválido: rol vacío
        $_POST['role'] = '';
        ob_start();
        include __DIR__ . '/../controllers/UserController.php';
        $output = ob_get_clean();
        $this->results['create_user_role_empty'] = strpos($output, 'error') !== false;
    }

    private function testCreateSchool() {
        echo "🏫 Probando Creación de Escuela...\n";
        $_POST = [
            'name' => 'Escuela Test',
            'address' => 'Calle 123',
            'phone' => '1234567',
            'csrf_token' => $this->csrfToken
        ];
        ob_start();
        include __DIR__ . '/../controllers/SchoolController.php';
        $output = ob_get_clean();
        $this->results['create_school_valid'] = strpos($output, 'error') === false;

        // Caso inválido: teléfono corto
        $_POST['phone'] = '123';
        ob_start();
        include __DIR__ . '/../controllers/SchoolController.php';
        $output = ob_get_clean();
        $this->results['create_school_phone_short'] = strpos($output, 'error') !== false;
    }

    private function testAddGrade() {
        echo "📊 Probando Agregar Calificación...\n";
        $_POST = [
            'student_id' => '1',
            'subject_id' => '1',
            'grade' => '4.5',
            'period' => '2024-1',
            'csrf_token' => $this->csrfToken
        ];
        ob_start();
        include __DIR__ . '/../controllers/GradeController.php';
        $output = ob_get_clean();
        $this->results['add_grade_valid'] = strpos($output, 'error') === false;

        // Caso inválido: calificación fuera de rango
        $_POST['grade'] = '6.0';
        ob_start();
        include __DIR__ . '/../controllers/GradeController.php';
        $output = ob_get_clean();
        $this->results['add_grade_out_of_range'] = strpos($output, 'error') !== false;
    }

    private function generateReport() {
        echo "\n==============================\n";
        echo "🧪 REPORTE DE PRUEBAS DE VALIDACIÓN\n";
        echo "==============================\n";
        foreach ($this->results as $test => $passed) {
            echo ($passed ? '✅' : '❌') . " $test\n";
        }
        echo "\n🎉 Pruebas de validación completadas.\n";
    }
}

$tester = new FormValidationTester();
?> 