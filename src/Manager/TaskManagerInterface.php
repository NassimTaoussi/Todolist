<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Task;

interface TaskManagerInterface {

    public function add(Task $task): void;

    public function update(Task $task): void;

    public function delete(Task $task): void;

    public function toggle(Task $task): void;
}