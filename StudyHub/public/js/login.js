document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const errorMessage = document.getElementById("error-message");

    form.addEventListener("submit", function(e) {
        e.preventDefault(); 

        const formData = new FormData(form);

        fetch("/includes/auth/login_handler.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json()) 
        .then(data => {
            if (data.success) {
                window.location.href = "/public/dashboard.php"; 
            } else {
                errorMessage.textContent = data.message; 
                errorMessage.style.display = "block";
            }
        })
        .catch(error => console.error("Erreur AJAX : ", error));
    });
});
