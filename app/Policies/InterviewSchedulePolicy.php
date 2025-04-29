<?php
// app/Policies/InterviewSchedulePolicy.php
namespace App\Policies;

use App\Models\Candidate;
use App\Models\InterviewSchedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InterviewSchedulePolicy
{
    use HandlesAuthorization;

    public function view(User $user, InterviewSchedule $interview)
    {
        return $user->isSuperAdmin() || 
               $interview->user_id === $user->id || 
               $interview->candidate->processed_by === $user->id;
    }

    public function create(User $user)
    {
        return $user->isHRD();
    }

    public function scheduleInterview(User $user, Candidate $candidate)
    {
        return $user->isHRD() && $candidate->results->isNotEmpty();
    }

    public function update(User $user, InterviewSchedule $interview)
    {
        return $user->isHRD() && $interview->user_id === $user->id;
    }

    public function approve(User $user, InterviewSchedule $interview)
    {
        return $user->isRegularUser() && 
               $interview->candidate->processed_by === $user->id &&
               $interview->isPending();
    }

    public function complete(User $user, InterviewSchedule $interview)
    {
        return ($user->isHRD() && $interview->user_id === $user->id) || 
               ($user->isRegularUser() && $interview->candidate->processed_by === $user->id);
    }
}