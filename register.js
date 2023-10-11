function subs1() {
    var fname = document.sub1.name.value;
    var name = document.sub1.uname.value;
    var password = document.sub1.password.value;
    var err = document.getElementById("errr");

    //check firstname is empty
    if (fname === "") {
        err.innerHTML = "First name is empty";
        return false;
    }
    //check length of first name length is between 5 to 8 character
    if (fname.length < 5 || fname.length > 8) {
        err.innerHTML = "First name should be between 5 and 8 characters";
        return false;
    }
    //check username is empty
    if (name === "") {
        err.innerHTML = "Username is empty";
        return false;
    }
    //check length of username is between 5 and 8 charactrer
    if (name.length < 5 || name.length > 8) {
        err.innerHTML = "Username should be between 5 and 8 characters";
        return false;
    }
    // check password is empty
    if (password === "") {
        err.innerHTML = "Password is empty";
        return false;
    }
    // check passwork length is between 5 and 8
    if (password.length < 5 || password.length > 10) {
        err.innerHTML = "Password should be between 5 and 10 characters";
        return false;
    }
    //check for any special character in the input
    if (containsXSS(fname) || containsXSS(name) || containsXSS(password)) {
        err.innerHTML = "Input contains potentially dangerous characters.";
        return false;
    }

    return true;
}
//fuction to check for ant special character
function containsXSS(input) {
    var xssPattern = /<(\s*)(\/\s*)?script(\s*|\s+[^>]*)>(.*?)<(\s*)(\/\s*)?script(\s*|\s+[^>]*)>/i;
    return xssPattern.test(input);
}
