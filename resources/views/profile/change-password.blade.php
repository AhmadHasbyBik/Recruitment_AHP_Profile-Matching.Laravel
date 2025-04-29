@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">Change Password</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password.update') }}" id="passwordChangeForm">
                        @csrf

                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" style="cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required
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
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
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

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                                <i class="fas fa-key mr-1"></i> Change Password
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
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
    
    .input-group-text {
        background-color: #f8f9fa;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentPasswordInput = document.getElementById('current_password');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordStrengthBar = document.querySelector('.password-strength-meter .progress-bar');
    const passwordStrengthText = document.querySelector('.password-strength-text');
    const passwordMatchText = document.getElementById('password-match-text');
    const submitBtn = document.getElementById('submit-btn');
    const togglePassword = document.querySelectorAll('.toggle-password');
    const toggleConfirmPassword = document.querySelector('.toggle-confirm-password');
    
    // Toggle password visibility for current password and new password
    togglePassword.forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const input = this.closest('.input-group').querySelector('input');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
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
    
    // Check current password field
    currentPasswordInput.addEventListener('input', updateSubmitButton);
    
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
        const currentPassword = currentPasswordInput.value;
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const strength = calculatePasswordStrength(password);
        
        const requirementsMet = (
            currentPassword.length > 0 &&
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