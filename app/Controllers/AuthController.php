<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Models/UserRepository.php';
require_once __DIR__ . '/../Helpers/JWTHelper.php';

class AuthController extends Controller {
    public function register($request) {
        $username = $request['username'] ?? null;
        $email = $request['email'] ?? null;
        $password = $request['password'] ?? null;
        $role = $request['role'] ?? 'user';

        // Validaciones
        if (!$username || !$email || !$password) {
            return $this->errorResponse("Todos los campos son obligatorios", 400);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->errorResponse("Email inválido", 400);
        }
        if (strlen($password) < 6) {
            return $this->errorResponse("La contraseña debe tener al menos 6 caracteres", 400);
        }

        $repo = new UserRepository();

        // Verificar si el usuario ya existe
        if ($repo->findByUsername($username)) {
            return $this->errorResponse("El nombre de usuario ya existe", 409);
        }
        if ($repo->findByEmail($email)) {
            return $this->errorResponse("El email ya está registrado", 409);
        }

        // Crear el usuario
        $user = $repo->create($username, $email, $password, $role);

        if ($user) {
            // Generar token JWT
            $token = JWTHelper::createToken(
                $user->getId(),
                $user->getUsername(),
                $user->getRole()
            );

            return $this->jsonResponse([
                'message' => '¡Bienvenido a Locería Melchorita! Usuario registrado exitosamente',
                'token' => $token,
                'user' => [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole()
                ]
            ], 201);
        }

        return $this->errorResponse("Error al registrar usuario", 500);
    }

    public function login($request) {
        $username = $request['username'] ?? null;
        $password = $request['password'] ?? null;

        if (!$username || !$password) {
            return $this->errorResponse("Usuario y contraseña son obligatorios", 400);
        }

        $repo = new UserRepository();
        $user = $repo->findByUsername($username);

        if (!$user) {
            return $this->errorResponse("Credenciales inválidas", 401);
        }

        // Verificar la contraseña
        if (!password_verify($password, $user->getPassword())) {
            return $this->errorResponse("Credenciales inválidas", 401);
        }

        // Generar token JWT
        $token = JWTHelper::createToken(
            $user->getId(),
            $user->getUsername(),
            $user->getRole()
        );

        return $this->jsonResponse([
            'message' => '¡Bienvenido a Locería Melchorita!',
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ]
        ]);
    }

    public function me($request) {
        $user = $request['auth_user'] ?? null;

        if (!$user) {
            return $this->errorResponse("No autenticado", 401);
        }

        $repo = new UserRepository();
        $userFromDb = $repo->findById($user['user_id']);

        if (!$userFromDb) {
            return $this->errorResponse("Usuario no encontrado", 404);
        }

        return $this->jsonResponse([
            'user' => [
                'id' => $userFromDb->getId(),
                'username' => $userFromDb->getUsername(),
                'email' => $userFromDb->getEmail(),
                'role' => $userFromDb->getRole(),
                'created_at' => $userFromDb->getCreatedAt()
            ]
        ]);
    }

    public function validateToken($request) {
        $token = $request['token'] ?? null;

        if (!$token) {
            return $this->errorResponse("Token no proporcionado", 400);
        }

        $payload = JWTHelper::decode($token);

        if (!$payload) {
            return $this->errorResponse("Token inválido o expirado", 401);
        }

        return $this->jsonResponse([
            'valid' => true,
            'payload' => $payload
        ]);
    }
}