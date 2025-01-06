// Mostrar alertas con animación
function showAlert(message, type = 'info') {
    const alertBox = document.createElement('div');
    alertBox.className = `alert alert-${type} fade-in`;
    alertBox.textContent = message;
    document.body.prepend(alertBox);

    setTimeout(() => {
        alertBox.remove();
    }, 3000);
}

// Validación de formularios y manejo del envío
document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.querySelector('#registerForm');
    const loginForm = document.querySelector('#loginForm');

    // Manejar el formulario de registro
    if (registerForm) {
        registerForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Evitar el envío normal del formulario

            const formData = new FormData(registerForm);

            fetch('register.php', {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === 'success') {
                        showAlert(data.message, 'success');
                        setTimeout(() => {
                            window.location.href = 'index.php'; // Redirigir a inicio de sesión después de 3 segundos
                        }, 3000);
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    showAlert('Ocurrió un error inesperado. Inténtalo nuevamente.', 'danger');
                });
        });
    }

    // Manejar el formulario de inicio de sesión
    if (loginForm) {
        loginForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Evitar el envío normal del formulario

            const formData = new FormData(loginForm);

            fetch('index.php', {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === 'success') {
                        showAlert('Inicio de sesión exitoso.', 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect; // Redirigir a la página correspondiente
                        }, 1000);
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    showAlert('Ocurrió un error inesperado. Inténtalo nuevamente.', 'danger');
                });
        });
    }
});
