<?php

namespace App\Listeners;

use LdapRecord\Laravel\Events\Import\Saved;

class AssignUserRole
{
    /**
     * Handle the event.
     * Properties pada Saved event: $object (LdapModel) dan $eloquent (EloquentModel)
     */
    public function handle(Saved $event): void
    {
        $user = $event->eloquent;   // Eloquent User model
        $ldapUser = $event->object; // LdapRecord model

        // Jika user adalah Mohammad Hud, berikan role administrator (ID 1)
        if (
            strtolower((string) $user->email) === 'mohammad.hud@rs.ui.ac.id' ||
            strtolower((string) $user->username) === 'mohammad.hud'
        ) {
            $user->role_id = 1; // Administrator
            $user->saveQuietly();
            return;
        }

        // Jika belum punya role, berikan default role Viewer (ID 4)
        if (is_null($user->role_id)) {
            $user->role_id = 4;
            $user->saveQuietly();
        }
    }
}
