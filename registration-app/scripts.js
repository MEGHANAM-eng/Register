$(document).ready(function() {
    $('#registrationForm').on('submit', function(event) {
        // Get password and confirm password values
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();

        // Check if password and confirm password match
        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            event.preventDefault();  // Prevent form submission
        }

        // Further client-side validation (if needed)
        // Add more validations here if necessary
    });
});
