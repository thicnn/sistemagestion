<h1>Iniciar Sesión</h1>
<form action="/sistemagestion/login" method="POST">
    <div class="form-group">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Entrar</button>
</form>

<style>
    /* Estilos específicos para el formulario */
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; color: #555; }
    input[type="email"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
    button:hover { background-color: #0056b3; }
</style>