/*
    script.js
    ----------
    Simple, beginner-friendly JavaScript for:
    1. Bootstrap-style client-side form validation (add/edit student forms)
    2. A confirmation popup before deleting a student

    NOTE: This is only a first line of defense for a nicer user experience.
    The real, trustworthy validation always happens in PHP on the server,
    because JavaScript can be bypassed by disabling it in the browser.
*/

// Run this once the page has fully loaded
document.addEventListener("DOMContentLoaded", function () {

    // ---------------------------------------------------
    // 1. Bootstrap client-side validation for any form
    //    that has the class "needs-validation"
    // ---------------------------------------------------
    const forms = document.querySelectorAll(".needs-validation");

    forms.forEach(function (form) {
        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                // Stop the form from submitting if something is invalid
                event.preventDefault();
                event.stopPropagation();
            }
            // Adds Bootstrap's validation styles (green/red borders)
            form.classList.add("was-validated");
        });
    });

    // ---------------------------------------------------
    // 2. Confirm before deleting a student
    //    Any link/button with class "btn-delete" will trigger this
    // ---------------------------------------------------
    const deleteButtons = document.querySelectorAll(".btn-delete");

    deleteButtons.forEach(function (btn) {
        btn.addEventListener("click", function (event) {
            const studentName = btn.getAttribute("data-name") || "this student";
            const confirmDelete = confirm(
                "Are you sure you want to delete " + studentName + "? This action cannot be undone."
            );
            if (!confirmDelete) {
                // Cancel the delete if the user clicks "Cancel"
                event.preventDefault();
            }
        });
    });

    // ---------------------------------------------------
    // 3. Simple phone number field: allow only digits while typing
    // ---------------------------------------------------
    const phoneInput = document.getElementById("phone");
    if (phoneInput) {
        phoneInput.addEventListener("input", function () {
            phoneInput.value = phoneInput.value.replace(/[^0-9]/g, "");
        });
    }
});