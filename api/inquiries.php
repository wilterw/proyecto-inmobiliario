<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getInquiries($db);
        break;
    case 'POST':
        createInquiry($db);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}

function getInquiries($db) {
    try {
        $query = "SELECT i.*, p.title as property_title, p.address as property_address 
                  FROM inquiries i 
                  LEFT JOIN properties p ON i.property_id = p.id 
                  ORDER BY i.created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $inquiries = $stmt->fetchAll();
        
        echo json_encode($inquiries);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener consultas: ' . $e->getMessage()]);
    }
}

function createInquiry($db) {
    try {
        $data = [];
        
        // Obtener datos del formulario
        if ($_POST) {
            $data = $_POST;
        } else {
            $input = json_decode(file_get_contents('php://input'), true);
            $data = $input ?: [];
        }
        
        // Validar datos requeridos
        if (empty($data['name']) || empty($data['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Nombre y email son requeridos']);
            return;
        }
        
        // Sanitizar datos
        $property_id = !empty($data['property_id']) ? intval($data['property_id']) : null;
        $name = htmlspecialchars(strip_tags($data['name']));
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars(strip_tags($data['phone'] ?? ''));
        $message = htmlspecialchars(strip_tags($data['message'] ?? ''));
        
        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Email inválido']);
            return;
        }
        
        $query = "INSERT INTO inquiries (property_id, name, email, phone, message) 
                  VALUES (:property_id, :name, :email, :phone, :message)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':property_id', $property_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':message', $message);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Consulta enviada exitosamente',
                'inquiry_id' => $db->lastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar la consulta']);
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al procesar la consulta: ' . $e->getMessage()]);
    }
}
?>
