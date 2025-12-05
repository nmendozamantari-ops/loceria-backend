<?php
// app/Middlewares/AuthMiddleware.php
require_once __DIR__ . '/../Helpers/JWTHelper.php';
require_once __DIR__ . '/../Models/UserRepository.php';

class AuthMiddleware {
    
    /**
     * Verificar que el usuario esté autenticado
     * Retorna el payload del token si es válido
     * Termina la ejecución con error 401 si no lo es
     */
    public static function authenticate() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$authHeader) {
            http_response_code(401);
            echo json_encode([
                'error' => 'Token no proporcionado',
                'message' => 'Debe incluir el header Authorization: Bearer {token}'
            ]);
            exit();
        }

        // Verificar formato: "Bearer {token}"
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode([
                'error' => 'Formato de token inválido',
                'message' => 'El formato debe ser: Authorization: Bearer {token}'
            ]);
            exit();
        }

        $token = $matches[1];
        $payload = JWTHelper::decode($token);

        if (!$payload) {
            http_response_code(401);
            echo json_encode([
                'error' => 'Token inválido o expirado',
                'message' => 'Por favor, inicie sesión nuevamente'
            ]);
            exit();
        }

        return $payload;
    }

    /**
     * Verificar que el usuario tenga uno de los roles requeridos
     * @param array $roles Roles permitidos (ej: ['admin', 'moderator'])
     */
    public static function requireRole($roles = []) {
        // Primero autenticar
        $payload = self::authenticate();
        
        $userRole = $payload['role'] ?? null;

        // Verificar si el usuario tiene alguno de los roles permitidos
        if (!in_array($userRole, $roles)) {
            http_response_code(403);
            echo json_encode([
                'error' => 'Acceso denegado',
                'message' => 'No tienes permisos para acceder a este recurso',
                'required_roles' => $roles,
                'your_role' => $userRole
            ]);
            exit();
        }

        return $payload;
    }

    /**
     * Autenticación opcional - no termina la ejecución si no hay token
     * Útil para rutas que cambian su comportamiento si el usuario está autenticado
     */
    public static function optional() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$authHeader) {
            return null;
        }

        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return null;
        }

        $token = $matches[1];
        return JWTHelper::decode($token);
    }
}