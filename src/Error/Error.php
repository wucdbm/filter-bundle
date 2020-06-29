<?php

namespace Wucdbm\Bundle\WucdbmFilterBundle\Error;

class Error {

    private string $message;
    private string $path;

    public function __construct(string $message, string $path) {
        $this->message = $message;
        $this->path = $path;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getPath(): string {
        return $this->path;
    }

}
