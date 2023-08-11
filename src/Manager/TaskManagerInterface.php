<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Task;

interface TaskManagerInterface {

    public function add(Task $task);

    public function update(Task $task);

    public function delete(Task $task);

    public function toggle(Task $task);
}