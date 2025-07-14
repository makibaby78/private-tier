<?php

namespace App\Traits;

use App\Models\User;

trait HasVisibility
{
    public function canViewBy(?User $viewer): bool
    {
        if ($this->visibility === 'public') return true;
        if (!$viewer) return false;
        if ($this->user_id === $viewer->id) return true;
        if ($this->visibility === 'friends' && $this->user->isFriendWith($viewer)) return true;
        return false;
    }
}
