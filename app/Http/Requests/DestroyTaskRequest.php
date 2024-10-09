<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $completed
 */
class DestroyTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $task = $this->route()->parameter('task');
        return $task->user_id === $this->user()->id;
    }
}
