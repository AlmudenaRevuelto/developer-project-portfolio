<?php

abstract class BaseController
{
    /**
     * Send a JSON response and end request.
     *
     * @param mixed $data
     * @param int $status
     */
    protected function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Send a JSON error response.
     */
    protected function errorResponse(string $message, int $status): void
    {
        $this->jsonResponse(['error' => $message], $status);
    }

    /**
     * Decode the request body JSON into an associative array.
     *
     * @return array
     */
    protected function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }
}
