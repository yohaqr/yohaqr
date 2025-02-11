<?php

namespace Yoha\Qr\Bootstrap;

class ErrorHandler
{
    protected static bool $debug = false;

    public function __construct(bool $debug = false)
    {
        self::$debug = $debug;
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public static function setDebug(bool $debug): void
    {
        self::$debug = $debug;
    }

    public function handleError(int $errno, string $errstr, string $errfile, int $errline): never
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public function handleException(\Throwable $exception): void
    {
        $statusCode = 500;
        if (method_exists($exception, 'getStatusCode')) {
            $statusCandidate = $exception->getStatusCode();
            if (is_numeric($statusCandidate)) {
                $statusCode = (int)$statusCandidate;
            }
        }

        http_response_code($statusCode);
        error_log("[$statusCode] " . $exception->getMessage() . " in " . $exception->getFile() . ":" . $exception->getLine());
        $this->renderErrorPage($exception);
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $exception = new \ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            );
            $this->handleException($exception);
        }
    }

    public function renderErrorPage(\Throwable $exception): never
    {
        $statusCode = http_response_code();
        $errorTitle = self::$debug ? $exception->getMessage() : 'Oops! Something went wrong.';

        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error {$statusCode} - Yoha QR</title>
            <style>
                :root {
                    --primary-color: #dc3545;
                    --background: #f8f9fa;
                    --text-color: #212529;
                    --card-bg: #ffffff;
                }

                @media (prefers-color-scheme: dark) {
                    :root {
                        --background: #1a1a1a;
                        --text-color: #f8f9fa;
                        --card-bg: #2d2d2d;
                    }
                }

                * {
                    box-sizing: border-box;
                }

                body {
                    font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
                    line-height: 1.5;
                    margin: 0;
                    padding: 2rem;
                    background-color: var(--background);
                    color: var(--text-color);
                    min-height: 100vh;
                }

                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                }

                .error-header {
                    text-align: center;
                    margin-bottom: 2rem;
                    padding-bottom: 1rem;
                    border-bottom: 1px solid rgba(0,0,0,0.1);
                }

                .error-code {
                    font-size: 5rem;
                    font-weight: 700;
                    color: var(--primary-color);
                    margin: 0;
                }

                .error-title {
                    margin: 0.5rem 0;
                    font-size: 1.5rem;
                }

                .error-card {
                    background: var(--card-bg);
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    padding: 1.5rem;
                    margin-bottom: 1.5rem;
                }

                .details-toggle {
                    cursor: pointer;
                    color: var(--primary-color);
                    user-select: none;
                    margin: 1rem 0;
                    display: inline-block;
                }

                .stack-trace {
                    font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
                    font-size: 0.9em;
                    white-space: pre-wrap;
                    background: rgba(0,0,0,0.05);
                    padding: 1rem;
                    border-radius: 4px;
                    max-height: 400px;
                    overflow: auto;
                }

                .stack-frame {
                    padding: 0.5rem 0;
                    border-bottom: 1px solid rgba(0,0,0,0.1);
                }

                .copy-button {
                    background: var(--primary-color);
                    color: white;
                    border: none;
                    padding: 0.5rem 1rem;
                    border-radius: 4px;
                    cursor: pointer;
                    float: right;
                }

                .debug-info {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 1rem;
                    margin-top: 1rem;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="error-header">
                    <h1 class="error-code">{$statusCode}</h1>
                    <h2 class="error-title">{$this->escape($errorTitle)}</h2>
                </div>
        HTML;

        if (self::$debug) {
            echo <<<HTML
                <button class="copy-button" onclick="copyErrorDetails()">Copy Details</button>
                
                <div class="error-card">
                    <h3>Exception Details</h3>
                    <p><strong>Type:</strong> {$this->escape(get_class($exception))}</p>
                    <p><strong>File:</strong> {$this->escape($exception->getFile())} (Line: {$this->escape((string)$exception->getLine())})</p>
                </div>

                <div class="error-card">
                    <h3>Stack Trace</h3>
                    <div class="stack-trace">
                        {$this->renderStackTrace($exception)}
                    </div>
                </div>

                <div class="debug-info">
                    <div class="error-card">
                        <h3>Request Details</h3>
                        {$this->renderRequestDetails()}
                    </div>

                    <div class="error-card">
                        <h3>Server Details</h3>
                        {$this->renderServerDetails()}
                    </div>
                </div>
            HTML;
        } else {
            echo <<<HTML
                <div class="error-card">
                    <p>Please contact support if the problem persists. Include the following error code when reporting: 
                        <strong>ERR_{$statusCode}_{$this->generateErrorCode()}</strong>
                    </p>
                </div>
            HTML;
        }

        echo <<<HTML
            </div>
            <script>
                function copyErrorDetails() {
                    const content = `Error {$statusCode}: {$this->escapeJs($errorTitle)}\n` +
                    `Exception: {$this->escapeJs(get_class($exception))}\n` +
                    `Message: {$this->escapeJs($exception->getMessage())}\n` +
                    `File: {$this->escapeJs($exception->getFile())}:{$this->escapeJs((string)$exception->getLine())}\n` +
                    `Stack Trace:\n{$this->escapeJs($exception->getTraceAsString())}`;

                    navigator.clipboard.writeText(content)
                        .then(() => alert('Error details copied to clipboard'))
                        .catch(err => console.error('Failed to copy:', err));
                }
            </script>
        </body>
        </html>
        HTML;

        exit();
    }

    private function renderStackTrace(\Throwable $exception): string
    {
        $trace = array_map(function (array $trace): string {
            $file = $trace['file'] ?? 'internal';
            $line = isset($trace['line']) ? (string)$trace['line'] : '0';
            $class = $trace['class'] ?? '';
            $type = $trace['type'] ?? '';
            $function = $trace['function']; // always exists
            $args = isset($trace['args']) ? $this->formatArgs($trace['args']) : '';

            return "<div class='stack-frame'>
                <strong>{$this->escape($file)}:{$this->escape($line)}</strong><br>
                {$this->escape($class)}{$this->escape($type)}{$this->escape($function)}({$this->escape($args)})
            </div>";
        }, $exception->getTrace());

        return implode("\n", $trace);
    }

    /**
     * @param array<mixed> $args
     */
    private function formatArgs(array $args): string
    {
        return implode(', ', array_map(function ($arg): string {
            if (is_object($arg)) {
                return $this->escape(get_class($arg));
            }
            if (is_array($arg)) {
                return 'Array';
            }
            if (is_string($arg)) {
                $truncated = substr($arg, 0, 50) . (strlen($arg) > 50 ? '...' : '');
                return "'" . $this->escape($truncated) . "'";
            }
            return var_export($arg, true);
        }, $args));
    }

    private function renderRequestDetails(): string
    {
        $method   = $this->escape(($_SERVER['REQUEST_METHOD']   ?? ''));
        $uri      = $this->escape(($_SERVER['REQUEST_URI']      ?? ''));
        $ip       = $this->escape(($_SERVER['REMOTE_ADDR']      ?? ''));
        $protocol = $this->escape(($_SERVER['SERVER_PROTOCOL']  ?? ''));
        return <<<HTML
            <p><strong>Method:</strong> {$method}</p>
            <p><strong>URI:</strong> {$uri}</p>
            <p><strong>IP:</strong> {$ip}</p>
            <p><strong>Protocol:</strong> {$protocol}</p>
        HTML;
    }

    private function renderServerDetails(): string
    {
        $phpVersion     = $this->escape(PHP_VERSION);
        $serverSoftware = $this->escape(($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'));
        $serverName     = $this->escape(($_SERVER['SERVER_NAME']     ?? 'Unknown'));
        return <<<HTML
            <p><strong>PHP Version:</strong> {$phpVersion}</p>
            <p><strong>Server Software:</strong> {$serverSoftware}</p>
            <p><strong>Server Name:</strong> {$serverName}</p>
        HTML;
    }

    private function generateErrorCode(): string
    {
        return bin2hex(random_bytes(4));
    }

    /**
     * Escapes a value for HTML output.
     *
     * @param mixed $value
     */
    private function escape(mixed $value): string
    {
        if (is_scalar($value)) {
            return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        }
        $json = json_encode($value);
        if ($json === false) {
            $json = '';
        }
        return htmlspecialchars($json, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Escapes a string for safe use in JavaScript.
     */
    private function escapeJs(string $value): string
    {
        return addslashes($value);
    }
}
