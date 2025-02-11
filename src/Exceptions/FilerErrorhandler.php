<?php

namespace App\Exceptions;

use Exception;

class FilerErrorHandler
{
    public function handle(Exception $e): void
    {
        // Log the error manually to a file
        $logMessage = sprintf(
            "[%s] Error: %s in %s on line %d\nTrace: %s\n\n",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

        file_put_contents(__DIR__ . '/../../logs/error.log', $logMessage, FILE_APPEND);

        // If running in a web environment, return a JSON response
        if (php_sapi_name() !== 'cli') {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'An error occurred while processing the file.']);
        }
    }
}
