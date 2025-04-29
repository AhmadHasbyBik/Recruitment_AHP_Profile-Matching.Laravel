<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    // Tambahkan relasi ini
    public function profileMatchingResults()
    {
        return $this->hasMany(ProfileMatchingResult::class, 'processed_by');
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isHRD()
    {
        return $this->role === 'hrd';
    }

    public function isDirektur()
    {
        return $this->role === 'direktur';
    }

    public function isRegularUser()
    {
        return $this->role === 'user';
    }

    public function interviewSchedules()
    {
        return $this->hasMany(InterviewSchedule::class);
    }

    public function canScheduleInterview()
    {
        return $this->isHRD();
    }

    public function canApproveInterview()
    {
        return $this->isRegularUser();
    }

    public function canManageInterviews(): bool
    {
        return $this->isHRD() || $this->isSuperAdmin();
    }

    public function canGiveInterviewFeedback(): bool
    {
        return $this->isRegularUser() || $this->isDirektur();
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
