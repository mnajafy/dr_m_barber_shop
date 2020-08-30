<?php
namespace core\web;
use Exception;
use Throwable;
use Framework;
use core\base\BaseObject;
class ErrorHandler extends BaseObject {
    public $errorAction;
    /**
     * @var Exception|null
     */
    public $exception;
    public function register() {
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handleException']);
        if (defined('HHVM_VERSION')) {
            set_error_handler([$this, 'handleHhvmError']);
        }
        else {
            set_error_handler([$this, 'handleError']);
        }
        register_shutdown_function([$this, 'handleFatalError']);
    }
    public function unregister() {
        restore_error_handler();
        restore_exception_handler();
    }
    public function handleException($exception) {
        $this->exception = $exception;
        try {
            $this->unregister();
            if (Framework::$app->getView() !== null) {
                Framework::$app->getView()->clear();
            }
            $this->clearOutput();
            $this->renderException($exception);
        }
        catch (Exception $e) {
            $this->handleFallbackExceptionMessage($e, $exception);
        }
        catch (Throwable $e) {
            $this->handleFallbackExceptionMessage($e, $exception);
        }
        $this->exception = null;
        exit(1);
    }
    public function handleHhvmError($code, $message, $file, $line, $context, $backtrace) {
        $exception = new \ErrorException($message, $code, $code, $file, $line);
        $this->handleException($exception);
    }
    public function handleError($code, $message, $file, $line) {
        $exception = new \ErrorException($message, $code, $code, $file, $line);
        $this->handleException($exception);
    }
    public function handleFatalError() {
        $error = error_get_last();
        if (isset($error['type']) && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING])) {
            $exception = new \ErrorException($error['message'], $error['type'], $error['type'], $error['file'], $error['line']);
            $this->handleException($exception);
        }
    }
    public function clearOutput() {
        for ($level = ob_get_level(); $level > 0; --$level) {
            if (!@ob_end_clean()) {
                ob_clean();
            }
        }
    }
    public function handleFallbackExceptionMessage($exception, $previousException) {
        $msg = "An Error occurred while handling another error:\n";
        $msg .= (string) $exception;
        $msg .= "\nPrevious exception:\n";
        $msg .= (string) $previousException;
        echo '<pre>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</pre>';
    }
    public function renderException($exception) {
        $response = new Response();
        $result   = Framework::$app->runAction($this->errorAction, ['exception' => $exception]);
        if ($result instanceof Response) {
            $response = $result;
        }
        else {
            $response->data = $result;
        }
        $response->send();
    }
}