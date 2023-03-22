<?php

namespace App\Models\Traits;

trait CurrentUser
{
    public function scopeMy($q, $field = 'user_id')
    {
        return $q->where($field, auth()->id());
    }
}
