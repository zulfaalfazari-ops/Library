
// ========== REGISTRATION FORM VALIDATION ==========
function validateRegistrationForm() {
    var isValid = true;
    clearErrors(['firstnameError', 'lastnameError', 'university_idError', 
                 'emailError', 'passwordError', 'confirm_passwordError', 
                 'facultyError', 'year_of_studyError', 'termsError']);
    
    // Required field checks
    isValid = checkRequired('firstname', 'firstnameError', 'First name is required') && isValid;
    isValid = checkRequired('lastname', 'lastnameError', 'Last name is required') && isValid;
    isValid = checkRequired('university_id', 'university_idError', 'University ID is required') && isValid;
    isValid = checkRequired('email', 'emailError', 'Email address is required') && isValid;
    isValid = checkRequired('password', 'passwordError', 'Password is required') && isValid;
    isValid = checkRequired('confirm_password', 'confirm_passwordError', 'Please confirm your password') && isValid;
    isValid = checkRequired('terms', 'termsError', 'You must agree to the Terms and Conditions') && isValid;
    
    // Regex validations
    var email = document.getElementById('email');
    if (email && email.value.trim() !== '') {
        isValid = validateEmailFormat('email', 'emailError') && isValid;
    }
    
    var uniId = document.getElementById('university_id');
    if (uniId && uniId.value.trim() !== '') {
        isValid = validateUniversityId('university_id', 'university_idError') && isValid;
    }
    
    // Length constraints
    var firstName = document.getElementById('firstname');
    if (firstName && firstName.value.trim() !== '') {
        isValid = validateTextLength('firstname', 'firstnameError', 2, 30, 'First name') && isValid;
    }
    
    var lastName = document.getElementById('lastname');
    if (lastName && lastName.value.trim() !== '') {
        isValid = validateTextLength('lastname', 'lastnameError', 2, 30, 'Last name') && isValid;
    }
    
    var password = document.getElementById('password');
    if (password && password.value !== '') {
        isValid = validatePasswordStrength('password', 'passwordError') && isValid;
        isValid = validateTextLength('password', 'passwordError', 6, 20, 'Password') && isValid;
    }
    
    // Logical validation (Password Match)
    var confirmPwd = document.getElementById('confirm_password');
    if (password && password.value !== '' && confirmPwd && confirmPwd.value !== '') {
        isValid = validatePasswordMatch('password', 'confirm_password', 'confirm_passwordError') && isValid;
    }
    
    return isValid;
}

// ========== LOGIN FORM VALIDATION ==========
function validateLoginForm() {
    var isValid = true;
    clearErrors(['loginEmailError', 'loginPasswordError']);
    
    // Required field checks
    isValid = checkRequired('loginEmail', 'loginEmailError', 'Email is required') && isValid;
    isValid = checkRequired('loginPassword', 'loginPasswordError', 'Password is required') && isValid;
    
    // Email format validation
    var email = document.getElementById('loginEmail');
    if (email && email.value.trim() !== '') {
        isValid = validateEmailFormat('loginEmail', 'loginEmailError') && isValid;
    }
    
    // Password length check
    var password = document.getElementById('loginPassword');
    if (password && password.value !== '' && password.value.length < 6) {
        showError('loginPasswordError', 'Password must be at least 6 characters');
        isValid = false;
    }
    
    return isValid;
}

// ========== QUESTIONNAIRE FORM VALIDATION ==========
function validateQuestionnaireForm() {
    var isValid = true;
    
    clearErrors(['nameError', 'emailError', 'ageError', 'memberError', 
                 'satisfactionError', 'ratingError']);
    
    var fullname = document.getElementById('q_fullname');
    var email = document.getElementById('q_email');
    var age = document.getElementById('q_age');
    var memberType = document.getElementById('q_memberType');
    
    // 1. NAME VALIDATION (Required + Pattern: letters/spaces only, min 3 chars)
    if (fullname) {
        var namePattern = /^[A-Za-z\s]{3,}$/;
        if (!fullname.value || fullname.value.trim() === '') {
            showError('nameError', 'Please enter your full name');
            isValid = false;
        } else if (!namePattern.test(fullname.value.trim())) {
            showError('nameError', 'Name must contain only letters and spaces (minimum 3 characters)');
            isValid = false;
        }
    }
    
    // 2. EMAIL VALIDATION (Required + Pattern: university email)
    if (email) {
        var emailPattern = /^[^\s@]+@([^\s@.,]+\.)*university\.edu\.om$/;
        if (!email.value || email.value.trim() === '') {
            showError('emailError', 'Please enter your email address');
            isValid = false;
        } else if (!emailPattern.test(email.value.trim())) {
            showError('emailError', 'Email must be a valid university email (e.g., name@university.edu.om)');
            isValid = false;
        }
    }
    
    // 3. AGE VALIDATION (Required + Range: 16-100)
    if (age) {
        var ageValue = parseInt(age.value);
        if (!age.value || age.value === '') {
            showError('ageError', 'Please enter your age');
            isValid = false;
        } else if (isNaN(ageValue) || ageValue < 16 || ageValue > 100) {
            showError('ageError', 'Age must be between 16 and 100 years');
            isValid = false;
        }
    }
    
    // 4. MEMBER TYPE VALIDATION (Required select)
    if (memberType && (!memberType.value || memberType.value === '')) {
        showError('memberError', 'Please select your member type');
        isValid = false;
    }
    
    // 5. SATISFACTION RADIO VALIDATION (Must select one)
    var satisfactionSelected = false;
    var satisfactionRadios = document.getElementsByName('satisfaction');
    for (var i = 0; i < satisfactionRadios.length; i++) {
        if (satisfactionRadios[i].checked) {
            satisfactionSelected = true;
            break;
        }
    }
    if (!satisfactionSelected) {
        showError('satisfactionError', 'Please select your satisfaction level');
        isValid = false;
    }
    
    // 6. WEBSITE RATING VALIDATION (Must select 1-5 stars)
    var ratingSelected = false;
    var ratingRadios = document.getElementsByName('website_rating');
    for (var i = 0; i < ratingRadios.length; i++) {
        if (ratingRadios[i].checked) {
            ratingSelected = true;
            break;
        }
    }
    if (!ratingSelected) {
        showError('ratingError', 'Please rate your website experience');
        isValid = false;
    }
    
    return isValid;
}

// ========== HELPER FUNCTIONS (Shared by all forms) ==========

// Required field check (works for text, checkbox, select, textarea)
function checkRequired(fieldId, errorId, message) {
    var field = document.getElementById(fieldId);
    
    if (!field) return true; // Field doesn't exist on this page
    
    // Handle checkbox
    if (field.type === 'checkbox') {
        if (!field.checked) {
            showError(errorId, message);
            return false;
        }
        return true;
    }
    
    // Handle select dropdown
    if (field.tagName === 'SELECT') {
        if (!field.value || field.value === '') {
            showError(errorId, message);
            return false;
        }
        return true;
    }
    
    // Handle text inputs, textareas
    if (!field.value || field.value.trim() === '') {
        showError(errorId, message);
        return false;
    }
    
    return true;
}

// Email validation using regex
function validateEmailFormat(emailId, errorId) {
    var email = document.getElementById(emailId);
    if (!email) return true;
    
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    if (!emailPattern.test(email.value.trim())) {
        showError(errorId, 'Invalid email format (e.g., name@domain.com)');
        return false;
    }
    return true;
}

// University ID validation (numeric, 6-8 digits)
function validateUniversityId(uniId, errorId) {
    var uniValue = document.getElementById(uniId);
    if (!uniValue) return true;
    
    var idPattern = /^\d{6,8}$/;
    
    if (!idPattern.test(uniValue.value.trim())) {
        showError(errorId, 'University ID must be 6-8 digits (numbers only)');
        return false;
    }
    return true;
}

// Text length validation
function validateTextLength(fieldId, errorId, minLen, maxLen, fieldName) {
    var field = document.getElementById(fieldId);
    if (!field || !field.value) return true;
    
    var value = field.value.trim();
    if (value.length < minLen || value.length > maxLen) {
        showError(errorId, fieldName + ' must be between ' + minLen + ' and ' + maxLen + ' characters');
        return false;
    }
    return true;
}

// Password strength validation
function validatePasswordStrength(pwdId, errorId) {
    var password = document.getElementById(pwdId);
    if (!password) return true;
    
    var strongPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{6,}$/;
    
    if (!strongPattern.test(password.value)) {
        showError(errorId, 'Password must contain at least 1 letter and 1 number');
        return false;
    }
    return true;
}

// Password match validation
function validatePasswordMatch(pwdId, confirmId, errorId) {
    var password = document.getElementById(pwdId);
    var confirm = document.getElementById(confirmId);
    
    if (!password || !confirm) return true;
    
    if (password.value !== confirm.value) {
        showError(errorId, 'Passwords do not match');
        return false;
    }
    return true;
}

// Show error message
function showError(errorId, message) {
    var errorSpan = document.getElementById(errorId);
    if (errorSpan) {
        errorSpan.innerHTML = message;
        errorSpan.style.color = 'red';
    }
}

// Clear specific error messages
function clearErrors(errorIds) {
    for (var i = 0; i < errorIds.length; i++) {
        var errorSpan = document.getElementById(errorIds[i]);
        if (errorSpan) {
            errorSpan.innerHTML = '';
        }
    }
}

// Real-time validation for registration form
function initRegistrationValidation() {
    var fields = ['firstname', 'lastname', 'university_id', 'email', 'password', 'confirm_password'];
    
    for (var i = 0; i < fields.length; i++) {
        var field = document.getElementById(fields[i]);
        if (field) {
            field.addEventListener('blur', function() {
                var errorId = this.id + 'Error';
                var errorSpan = document.getElementById(errorId);
                if (errorSpan) errorSpan.innerHTML = '';
                
                switch(this.id) {
                    case 'firstname':
                        checkRequired('firstname', 'firstnameError', 'First name required');
                        validateTextLength('firstname', 'firstnameError', 2, 30, 'First name');
                        break;
                    case 'lastname':
                        checkRequired('lastname', 'lastnameError', 'Last name required');
                        validateTextLength('lastname', 'lastnameError', 2, 30, 'Last name');
                        break;
                    case 'university_id':
                        checkRequired('university_id', 'university_idError', 'University ID required');
                        validateUniversityId('university_id', 'university_idError');
                        break;
                    case 'email':
                        checkRequired('email', 'emailError', 'Email required');
                        validateEmailFormat('email', 'emailError');
                        break;
                    case 'password':
                        checkRequired('password', 'passwordError', 'Password required');
                        validatePasswordStrength('password', 'passwordError');
                        validateTextLength('password', 'passwordError', 6, 20, 'Password');
                        break;
                    case 'confirm_password':
                        if (document.getElementById('password') && document.getElementById('password').value !== '') {
                            validatePasswordMatch('password', 'confirm_password', 'confirm_passwordError');
                        }
                        break;
                }
            });
        }
    }
    
    var termsCheck = document.getElementById('termsCheck');
    if (termsCheck) {
        termsCheck.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('termsError').innerHTML = '';
            }
        });
    }
}

// Auto-detect which page and initialize appropriate validation
window.onload = function() {
    // Check which form exists on this page and initialize
    if (document.getElementById('registerForm')) {
        initRegistrationValidation();
    }
    
    if (document.getElementById('loginForm')) {
        // Login real-time validation can be added here if needed
    }
    
    if (document.getElementById('questionnaireForm')) {
        // Questionnaire real-time validation is handled inside questionnaire.html
    }
};
