function validateForm() {
    var x = document.forms["signup"]["password"].value;
    var y = document.forms["signup"]["repassword"].value;
    if (x != y) {
        alert("Passwords not matching!! Renter");
        return false;
    }
}