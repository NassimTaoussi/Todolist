<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;

interface UserManagerInterface {

    public function add(User $user);

    public function update(User $user);

    public function delete(User $user);

}