<?php

class User {
    // Guardaremos la conexión a la BD aquí
    private $connection;
    private $table_name = "usuarios";

    // El constructor recibe la conexión y la guarda
    public function __construct($db_connection) {
        $this->connection = $db_connection;
    }

    /**
     * Busca un usuario en la base de datos por su dirección de email.
     * @param string $email El email del usuario a buscar.
     * @return array|null Los datos del usuario si se encuentra, o null si no.
     */
    public function findByEmail($email) {
        // La consulta SQL para seleccionar al usuario
        $query = "SELECT id, nombre, email, password_hash, rol FROM " . $this->table_name . " WHERE email = ? LIMIT 1";

        // Preparamos la consulta para evitar inyección SQL
        $stmt = $this->connection->prepare($query);

        // Vinculamos el email del parámetro (?) a la consulta
        $stmt->bind_param("s", $email); // "s" significa que es un string

        // Ejecutamos la consulta
        $stmt->execute();

        // Obtenemos el resultado
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Si encontramos al usuario, devolvemos sus datos
            return $result->fetch_assoc();
        } else {
            // Si no, devolvemos null
            return null;
        }
    }
}