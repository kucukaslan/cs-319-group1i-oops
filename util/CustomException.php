<?php
class UserDoesNotExistsException extends PDOException {
    public function __construct(string $message = "The user does not exists.", int $code = 0, ?Throwable $previous = null) {
            parent::__construct($message, $code, $previous);
    }
}

class PasswordIsWrongException extends PDOException {
    public function __construct(string $message = "The Password is wrong", int $code = 0, ?Throwable $previous = null) {
            parent::__construct($message, $code, $previous);
    }
}

class EmailAlreadyExistsException extends PDOException {
    public function __construct(string $message = "The email already exists", int $code = 0, ?Throwable $previous = null) {
            parent::__construct($message, $code, $previous);
    }
}

class UserIdAlreadyExistsException extends PDOException {
    public function __construct(string $message = "The user id already exists", int $code = 0, ?Throwable $previous = null) {
            parent::__construct($message, $code, $previous);
    }
}