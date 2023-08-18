<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;

interface UserManagerInterface {

    public function add(User $user): void;

    public function update(User $user): void;

    public function delete(User $user): void;

}