<!-- vistas/login.php -->
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="estilo.css" />
  <title>Login</title>
</head>

<body>
  <h1>Login</h1>

  <form action="index.php" method="POST" id="form-login" class="diseño">
    <label for="correo">Correo:</label>
    <input type="email" name="correo" id="correo" required /><br />

    <label for="contraseña">Contraseña:</label>
    <input type="password" name="contraseña" id="contraseña" required /><br />

    <button type="submit">Iniciar sesión</button>
  </form>

  <script>
    document.getElementById("form-login").addEventListener("submit", function(event) {
      event.preventDefault();

      var correo = document.getElementById("correo").value;
      var contraseña = document.getElementById("contraseña").value;

      var datos = {
        correo: correo,
        contraseña: contraseña,
      };

      fetch("index.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(datos),
        })
        .then((response) => response.json())
        .then((data) => {
          if (data.mensaje === "Inicio de sesión exitoso") {
            alert("Bienvenido " + data.usuario);
            window.location.href = data.redirect; // Redirigir al inicio
          } else {
            alert(data.mensaje);
          }
        })
        .catch((error) => {
          console.error("Error al hacer la solicitud:", error);
        });
    });
  </script>
</body>

</html>