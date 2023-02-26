<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        // original
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        //
        // original
        return $user->belongsToTeam($team);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        // original
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        return $user->teamRole($team)->name == "Owner" ||
            $user->teamRole($team)->name == "Administrator" ||
            $user->teamRole($team)->name == "Editor";
        //
        // original
        // return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can add team members.
     */
    public function addTeamMember(User $user, Team $team): bool
    {
        return $user->teamRole($team)->name == "Owner" ||
            $user->teamRole($team)->name == "Administrator";
        //
        // original
        // return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can update team member permissions.
     */
    public function updateTeamMember(User $user, Team $team): bool
    {
        return $user->teamRole($team)->name == "Owner" ||
            $user->teamRole($team)->name == "Administrator";
        //
        // original
        // return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can remove team members.
     */
    public function removeTeamMember(User $user, Team $team): bool
    {
        return $user->teamRole($team)->name == "Owner" ||
            $user->teamRole($team)->name == "Administrator";
        //
        // original
        // return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->teamRole($team)->name == "Owner";
        //
        // original
        // return $user->ownsTeam($team);
    }

    /**
     * General actions
     *
     *
     *
     *
     *
     */

    /**
     * Determine whether the user can remove resource.
     */

    public function canCreateUpdateAndDelete(User $user, Team $team): bool
    {
        dd("cøme here to this policy!!!!");
        return $user->teamRole($team)->name == "Owner" ||
            $user->teamRole($team)->name == "Administrator";
    }
}
