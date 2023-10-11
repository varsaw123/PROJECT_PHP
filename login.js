function validateform() {
    var name = document.Submission.uname.value;
    var password = document.Submission.password.value;
    var err = document.getElementById("error");

    // Client-side validation to prevent XSS
    if (/<[^>]*>/g.test(name) || /<[^>]*>/g.test(password)) {
        err.innerHTML = "Input contains invalid characters.";
        return false;
    }

    // check if name field is empty
    if (name === "") {
        err.innerHTML = "Name field is empty";
        return false;
    }
    // check if name length is betweem 5-8 character
    if (name.length > 8 || name.length < 5) {
        err.innerHTML = "Please enter a valid username (5-8 characters)";
        return false;
    }
    //check if password field is empty
    if (password === "") {
        err.innerHTML = "Password field is empty";
        return false;
    }
    // check if password length is between 5 and 10 character
    if (password.length > 10 || password.length < 5) {
        err.innerHTML = "Please enter a valid password (5-10 characters)";
        return false;
    }

    return true;
}