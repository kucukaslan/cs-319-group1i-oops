<?php
class UserDoesNotExistsException extends RuntimeException {
    public function __construct(string $message = "The user does not exists.", int $code = 0, ?Throwable $previous = null) {
            parent::__construct($message, $code, $previous);
    }
}

class PasswordIsWrongException extends RuntimeException {
    public function __construct(string $message = "The Password is wrong", int $code = 0, ?Throwable $previous = null) {
            parent::__construct($message, $code, $previous);
    }
}