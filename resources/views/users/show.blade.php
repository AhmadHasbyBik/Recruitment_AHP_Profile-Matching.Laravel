@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User: {{ $user->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Name</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>
                                        @switch($user->role)
                                            @case('super_admin')
                                                <span class="badge badge-danger">Super Admin</span>
                                                @break
                                            @case('hrd')
                                                <span class="badge badge-primary">HR Department</span>
                                                @break
                                            @case('direktur')
                                                <span class="badge badge-success">Direktur</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">Regular User</span>
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Change Password</h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('users.change-password', $user->id) }}" method="POST" id="passwordForm">
                                        @csrf
                                        <div class="form-group">
                                            <label for="new-password">New Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" 
                                                    id="new-password" name="password" required
                                                    minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                    title="Must contain at least one number, one uppercase and lowercase letter, and at least 8 or more characters">
                                                <div class="input-group-append">
                                                    <span class="input-group-text toggle-password" style="cursor: pointer;">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="password-strength-meter mt-2">
                                                <div class="progress" style="height: 5px;">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                                </div>
                                                <small class="password-strength-text text-muted">Password strength</small>
                                            </div>
                                            <div class="password-requirements mt-2">
                                                <ul class="list-unstyled">
                                                    <li class="requirement length" data-requirement="length">
                                                        <i class="fas fa-circle"></i> At least 8 characters
                                                    </li>
                                                    <li class="requirement uppercase" data-requirement="uppercase">
                                                        <i class="fas fa-circle"></i> At least 1 uppercase letter
                                                    </li>
                                                    <li class="requirement lowercase" data-requirement="lowercase">
                                                        <i class="fas fa-circle"></i> At least 1 lowercase letter
                                                    </li>
                                                    <li class="requirement number" data-requirement="number">
                                                        <i class="fas fa-circle"></i> At least 1 number
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="new-password-confirm">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" 
                                                    id="new-password-confirm" name="password_confirmation" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text toggle-confirm-password" style="cursor: pointer;">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="password-match-indicator mt-2">
                                                <small id="password-match-text" class="text-muted"></small>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                                            <i class="fas fa-key"></i> Change Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .password-strength-meter {
        margin-bottom: 10px;
    }
    
    .password-requirements ul {
        margin-bottom: 0;
    }
    
    .password-requirements li {
        font-size: 0.8rem;
        margin-bottom: 2px;
    }
    
    .password-requirements li .fas {
        font-size: 0.5rem;
        vertical-align: middle;
        margin-right: 5px;
    }
    
    .requirement.valid {
        color: #28a745;
    }
    
    .requirement.valid .fas {
        color: #28a745;
    }
    
    .progress-bar {
        transition: width 0.3s ease, background-color 0.3s ease;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('new-password');
    const confirmPasswordInput = document.getElementById('new-password-confirm');
    const passwordStrengthBar = document.querySelector('.password-strength-meter .progress-bar');
    const passwordStrengthText = document.querySelector('.password-strength-text');
    const passwordMatchText = document.getElementById('password-match-text');
    const submitBtn = document.getElementById('submit-btn');
    const togglePassword = document.querySelector('.toggle-password');
    const toggleConfirmPassword = document.querySelector('.toggle-confirm-password');
    
    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const icon = this.querySelector('i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Toggle confirm password visibility
    toggleConfirmPassword.addEventListener('click', function() {
        const icon = this.querySelector('i');
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            confirmPasswordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Check password strength
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);
        updatePasswordStrengthUI(strength);
        checkPasswordMatch();
        validatePasswordRequirements(password);
    });
    
    // Check password match
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        
        // Length requirement
        if (password.length >= 8) strength += 1;
        if (password.length >= 12) strength += 1;
        
        // Contains numbers
        if (/\d/.test(password)) strength += 1;
        
        // Contains lowercase and uppercase
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;
        
        // Contains special characters
        if (/[^a-zA-Z0-9]/.test(password)) strength += 1;
        
        return Math.min(strength, 5); // Max strength is 5
    }
    
    function updatePasswordStrengthUI(strength) {
        let width = 0;
        let color = '';
        let text = '';
        
        switch(strength) {
            case 0:
                width = 5;
                color = '#dc3545';
                text = 'Very Weak';
                break;
            case 1:
                width = 25;
                color = '#dc3545';
                text = 'Weak';
                break;
            case 2:
                width = 50;
                color = '#fd7e14';
                text = 'Fair';
                break;
            case 3:
                width = 75;
                color = '#ffc107';
                text = 'Good';
                break;
            case 4:
                width = 90;
                color = '#28a745';
                text = 'Strong';
                break;
            case 5:
                width = 100;
                color = '#28a745';
                text = 'Very Strong';
                break;
        }
        
        passwordStrengthBar.style.width = width + '%';
        passwordStrengthBar.style.backgroundColor = color;
        passwordStrengthText.textContent = 'Password strength: ' + text;
        passwordStrengthText.style.color = color;
    }
    
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (password && confirmPassword) {
            if (password === confirmPassword) {
                passwordMatchText.textContent = 'Passwords match!';
                passwordMatchText.style.color = '#28a745';
            } else {
                passwordMatchText.textContent = 'Passwords do not match!';
                passwordMatchText.style.color = '#dc3545';
            }
        } else {
            passwordMatchText.textContent = '';
        }
        
        updateSubmitButton();
    }
    
    function validatePasswordRequirements(password) {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password)
        };
        
        Object.keys(requirements).forEach(key => {
            const element = document.querySelector(`.requirement.${key}`);
            if (requirements[key]) {
                element.classList.add('valid');
            } else {
                element.classList.remove('valid');
            }
        });
        
        updateSubmitButton();
    }
    
    function updateSubmitButton() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const strength = calculatePasswordStrength(password);
        
        const requirementsMet = (
            password.length >= 8 &&
            /[A-Z]/.test(password) &&
            /[a-z]/.test(password) &&
            /\d/.test(password) &&
            password === confirmPassword &&
            strength >= 3
        );
        
        submitBtn.disabled = !requirementsMet;
    }
});
</script>
@endsection