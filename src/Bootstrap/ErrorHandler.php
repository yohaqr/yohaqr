<?php

namespace Yoha\Qr\Bootstrap;

class ErrorHandler
{
    public function __construct()
    {
        // Set custom error and exception handlers
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * Converts PHP errors into exceptions.
     * @return never
     */
    public function handleError(int $errno, string $errstr, string $errfile, int $errline): never
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }


    /**
     * Handles all uncaught exceptions.
     */
    public function handleException(\Throwable $exception): void // FIX: Use \Throwable
    {
        http_response_code(500); // Set response code to 500 (Internal Server Error)

        // Log the error (optional)
        error_log($exception->getMessage());

        // Display a beautiful error page
        $this->renderErrorPage($exception);
    }

    /**
     * Renders a custom error page.
     */
    private function renderErrorPage(\Throwable $exception): never
    {
        echo "
        <html>
        <head>
            <title>Error Occurred</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f8f9fa; text-align: center; padding: 50px; }
                .container { max-width: 600px; margin: auto; padding: 20px; background: white; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
                h1 { color: red; }
                .error-details { margin-top: 20px; text-align: left; }
                pre { background: #eee; padding: 10px; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>Oops! Something went wrong.</h1>
                <p>An unexpected error occurred. Please try again later.</p>
                <div class='error-details'>
                    <h3>Error Details:</h3>
                    <pre><strong>Exception:</strong> " . get_class($exception) . "</pre>
                    <pre><strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "</pre>
                    <pre><strong>File:</strong> " . $exception->getFile() . " (Line " . $exception->getLine() . ")</pre>
                </div>
            </div>
        </body>
        </html>
        ";

        exit();
    }
}
