<h1>Dashboard Principal</h1>
<p>¡Bienvenido de nuevo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>

<nav>
    <a href="/sistemagestion/clients" class="button">Gestionar Clientes</a>
    </nav>
<br>
<a href="/sistemagestion/logout">Cerrar Sesión</a>