document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("registrationForm");
    const errorMessage = document.createElement("p");
    errorMessage.style.color = "red";
    form.appendChild(errorMessage);

    document.getElementById('nom').addEventListener('input', function() {
        this.value = this.value.toUpperCase(); 
    });

    document.getElementById('prenom').addEventListener('input', function() {
        this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase(); 
    });

    form.addEventListener("submit", function(e) {
        e.preventDefault(); 

        const formData = new FormData(form);

        fetch("/includes/auth/register_handler.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json()) 
        .then(data => {
            if (data.success) {
                window.location.href = "/public/login.php"; 
            } else {
                errorMessage.textContent = data.message; 
            }
        })
        .catch(error => console.error("Erreur AJAX : ", error));
    });
});

document.getElementById('mdp').addEventListener('input', function() {
    const password = this.value;
    const errorMessage = document.querySelector('.form-container .error-message');

    if (password.length < 8) {
        errorMessage.textContent = "Le mot de passe doit contenir au moins 8 caractères.";
    } else {
        errorMessage.textContent = ""; 
    }
});
