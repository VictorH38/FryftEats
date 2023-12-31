/**** Login/Sign Up ****/
document.addEventListener("DOMContentLoaded", function() {
    var urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('login-error') === 'true') {
        var errorMessageDiv = document.getElementById('login-error-message');
        errorMessageDiv.style.display = 'block';
    }
    else {
		var errorMessageDiv = document.getElementById('login-error-message');
        errorMessageDiv.style.display = 'none';
    }
    
    if (urlParams.get('signup-error') === 'true') {
        var errorMessageDiv = document.getElementById('signup-error-message');
        errorMessageDiv.innerHTML = "An account with that username or email already exists";
        errorMessageDiv.style.display = 'block';
    }
    else {
		var errorMessageDiv = document.getElementById('signup-error-message');
        errorMessageDiv.innerHTML = "";
    }
    
    var signupForm = document.getElementById("signup-form");

    signupForm.addEventListener("submit", function(event) {
        var email = document.getElementById("email").value;
        var password = document.getElementById("signup-password").value;
        var confirmPassword = document.getElementById("confirm-password").value;
        var terms = document.getElementById("terms").checked;
        var errorMessage = "";

        if (!email.includes('@') || email.lastIndexOf('@') === email.length - 1) {
            errorMessage += "Email must contain an '@' and a domain name";
        } else if (password !== confirmPassword) {
            errorMessage += "Passwords do not match. ";
        } else if (!terms) {
            errorMessage += "You must agree to the terms and conditions. ";
        }

        if (errorMessage.length > 0) {
            event.preventDefault();
            document.getElementById("signup-error-message").innerHTML = errorMessage;
        }
    });
});

/**** Search ****/
let search_button = document.getElementById("search-button");
search_button.addEventListener('click', function(event) {
    var priceValues = '';
    var priceOptions = document.getElementsByClassName('price-option');

    for (var i = 0; i < priceOptions.length; i++) {
        if (priceOptions[i].checked) {
            priceValues += priceOptions[i].id.replace('price', '');
        }
    }

    if (priceValues === '') {
        priceValues = '0';
    }

    let price = document.getElementById("price");
    price.value = priceValues;
});

function setSortBy(option) {
    let sort_by = document.getElementById("sort-by");
    sort_by.value = option;
}

function toggleDisplay(element) {
    if (element.style.display === "none" || element.style.display === "") {
        element.style.display = "block";
    }
    else {
        element.style.display = "none";
    }
}

function displayPriceOptions(event) {
    let priceOptions = document.getElementById("price-options");
    let sortOptions = document.getElementById("sort-options");

    sortOptions.style.display = "none";
    toggleDisplay(priceOptions);
    event.stopPropagation();
}

function displaySortOptions(event) {
    let sortOptions = document.getElementById("sort-options");
    let priceOptions = document.getElementById("price-options");

    priceOptions.style.display = "none";
    toggleDisplay(sortOptions);

    event.stopPropagation();
}

let priceButton = document.getElementById("price-button");
let priceSortIcon = document.getElementById("price-sort-icon");
let priceCaretIcon = document.getElementById("price-caret-icon");
let sortButton = document.getElementById("sort-button");
let sortIcon = document.getElementById("sort-icon");
let caretIcon = document.getElementById("caret-icon");

priceButton.addEventListener("click", displayPriceOptions);
priceSortIcon.addEventListener("click", displayPriceOptions);
priceCaretIcon.addEventListener("click", displayPriceOptions);
sortButton.addEventListener("click", displaySortOptions);
sortIcon.addEventListener("click", displaySortOptions);
caretIcon.addEventListener("click", displaySortOptions);

document.addEventListener("click", function(event) {
    let priceOptions = document.getElementById("price-options");
    let sortButton = document.getElementById("sort-button");
    let sortOptions = document.getElementById("sort-options");

    if (!priceOptions.contains(event.target)) {
        priceOptions.style.display = "none";
    }

    if (!sortButton.contains(event.target)) {
        sortOptions.style.display = "none";
    }
});

sortButton.addEventListener("click", function() {
    let sort_by = document.getElementById("sort-by");
    let best_match = document.getElementById("sort-best-match");
    let rating = document.getElementById("sort-rating");
    let review_count = document.getElementById("sort-review-count");
    let distance = document.getElementById("sort-distance");

    if (sort_by.value == "best_match") {
        best_match.style.color = "deepskyblue";
        rating.style.color = "black";
        review_count.style.color = "black";
        distance.style.color = "black";
    }
    else if (sort_by.value == "rating") {
        best_match.style.color = "black";
        rating.style.color = "deepskyblue";
        review_count.style.color = "black";
        distance.style.color = "black";
    }
    else if (sort_by.value == "review_count") {
        best_match.style.color = "black";
        rating.style.color = "black";
        review_count.style.color = "deepskyblue";
        distance.style.color = "black";
    }
    else if (sort_by.value == "distance") {
        best_match.style.color = "black";
        rating.style.color = "black";
        review_count.style.color = "black";
        distance.style.color = "deepskyblue";
    }
});
