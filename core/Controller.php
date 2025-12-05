<?php

class Controller {
    protected function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function errorResponse($message, $status = 400) {
        $this->jsonResponse(["error" => $message], $status);
    }
}
