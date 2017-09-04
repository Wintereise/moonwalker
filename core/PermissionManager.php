<?php

namespace Moonwalker\Core;

class PermissionManager
{
    private $userId;
    public function __construct ($userId)
    {
        $this->userId = $userId;
    }

    public static function with ($userId)
    {
        return new static($userId);
    }

    public function verify ($permissionName, $target)
    {
        /*
         * Pass 1, individual verification.
         * Failing this, we'll try to look it up via roles.
         */



    }

}