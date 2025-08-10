<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <!--<form action="apiUsuarios.php" method="get">
        <input type="hidden" name="accion" value="getUsers">
        <input type="submit" value="">
    </form>-->
    
    <input type="hidden" id="action" value="getUsers">
    <button onclick="mostrarUsuarios()">Mostrar Usuario</button>

    <script>
        function mostrarUsuarios(){

            const accion = document.getElementById('action').value;
            const url = `apiUsuarios.php?accion=${encodeURIComponent(accion)}`;

            fetch(url)
            .then(response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return response.json();
            } else {
                throw new Error("La respuesta no es JSON válida");
            }
            })
            .then(data => {
               echo(data);
            })
            .catch(error => {
            console.error('Error:', error);
            alert("Ocurrió un error al mostrar usuarios.");
            });
        }
    </script>

</body>
</html>