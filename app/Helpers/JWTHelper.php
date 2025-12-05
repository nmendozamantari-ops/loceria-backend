<?php
// app/Helpers/JWTHelper.php
class JWTHelper {
    // ⚠️ CAMBIAR EN PRODUCCIÓN - Esta debe ser una clave única y segura
    private static $secret_key = "tu_clave_secreta_muy_segura_2024_cambiar_en_produccion";
    private static $algorithm = 'HS256';

    /**
     * Codifica un payload en un token JWT
     * @param array $payload Datos a codificar
     * @return string Token JWT
     */
    public static function encode($payload) {
        $header = [
            'typ' => 'JWT',
            'alg' => self::$algorithm
        ];

        $header = self::base64UrlEncode(json_encode($header));
        $payload = self::base64UrlEncode(json_encode($payload));
        
        $signature = hash_hmac('sha256', "$header.$payload", self::$secret_key, true);
        $signature = self::base64UrlEncode($signature);

        return "$header.$payload.$signature";
    }

    /**
     * Decodifica y valida un token JWT
     * @param string $jwt Token a decodificar
     * @return array|null Payload decodificado o null si es inválido
     */
    public static function decode($jwt) {
        $parts = explode('.', $jwt);
        
        if (count($parts) !== 3) {
            return null;
        }

        list($header, $payload, $signature) = $parts;

        // Verificar la firma
        $valid_signature = hash_hmac('sha256', "$header.$payload", self::$secret_key, true);
        $valid_signature = self::base64UrlEncode($valid_signature);

        if ($signature !== $valid_signature) {
            return null;
        }

        // Decodificar el payload
        $payload = json_decode(self::base64UrlDecode($payload), true);

        // Verificar expiración
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return null;
        }

        return $payload;
    }

    /**
     * Crea un token JWT para un usuario
     * @param int $user_id ID del usuario
     * @param string $username Nombre de usuario
     * @param string $role Rol del usuario
     * @param int $expiration_hours Horas hasta la expiración (default: 24)
     * @return string Token JWT
     */
    public static function createToken($user_id, $username, $role, $expiration_hours = 24) {
        $issued_at = time();
        $expiration = $issued_at + ($expiration_hours * 3600);

        $payload = [
            'iat' => $issued_at,          // Issued at (cuándo se creó)
            'exp' => $expiration,         // Expiration (cuándo expira)
            'user_id' => $user_id,
            'username' => $username,
            'role' => $role
        ];

        return self::encode($payload);
    }

    /**
     * Codifica en Base64 URL-safe
     */
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Decodifica desde Base64 URL-safe
     */
    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}