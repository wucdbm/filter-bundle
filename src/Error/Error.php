<?php

/*
 * This file is part of the WucdbmFilterBundle package.
 *
 * Copyright (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * Author Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
