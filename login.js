const rootPhp = "./usuarioController.php";
const formularioLogin = document.getElementById('formularioLogin');

formularioLogin.addEventListener('submit', function (e) {
    e.preventDefault();

    const correo = document.getElementById('correo').value.trim();
    const password = document.getElementById('password').value.trim();
    const tipoUsuario = document.getElementById('tipoUsuario').innerText.replace('Registrando como: ', '').trim();

    const datosLogin = {
        correo,
        password,
        tipoUsuario
    };

    fetch(`${rootPhp}?action=login`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datosLogin)
    })
        .then(response => response.json())
        .then(data => {
            console.log("🚀 ~ data:", data)
            if (data.success) {
                window.location.href = 'biblioteca.html';
                sessionStorage.setItem('loggedIn', 'true');
                sessionStorage.setItem('loginTime', Date.now());
            } else {
                alert('❌ Permiso denegado: ' + data.message);
            }
        })
        .catch(error => {
            console.error('❌ Error en la petición:', error);
            alert('Error en el servidor.');
        });
});
