<?php

class Client
{
    private $connection;
    private $table_name = "clientes";

    public function __construct($db_connection)
    {
        $this->connection = $db_connection;
    }

    /**
     * Recupera todos los clientes de la base de datos.
     * @return array Un array con todos los clientes.
     */
    public function findAll()
    {
        $query = "SELECT id, nombre, telefono, email FROM " . $this->table_name . " ORDER BY nombre ASC";

        $result = $this->connection->query($query);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crea un nuevo cliente en la base de datos.
     * @param string $nombre
     * @param string $telefono
     * @param string $email
     * @param string $notas
     * @return bool True si fue exitoso, false si falló.
     */
    public function create($nombre, $telefono, $email, $notas)
    {
        $query = "INSERT INTO " . $this->table_name . " (nombre, telefono, email, notas) VALUES (?, ?, ?, ?)";

        $stmt = $this->connection->prepare($query);

        // Limpiamos los datos para evitar problemas de seguridad
        $nombre = htmlspecialchars(strip_tags($nombre));
        $telefono = htmlspecialchars(strip_tags($telefono));
        $email = htmlspecialchars(strip_tags($email));
        $notas = htmlspecialchars(strip_tags($notas));

        $stmt->bind_param("ssss", $nombre, $telefono, $email, $notas);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    /**
     * Busca un cliente por su ID.
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        $query = "SELECT id, nombre, telefono, email, notas FROM " . $this->table_name . " WHERE id = ? LIMIT 1";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id); // "i" significa que es un integer
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    /**
     * Actualiza los datos de un cliente existente.
     * @param int $id
     * @param string $nombre
     * @param string $telefono
     * @param string $email
     * @param string $notas
     * @return bool
     */
    public function update($id, $nombre, $telefono, $email, $notas)
    {
        $query = "UPDATE " . $this->table_name . " SET nombre = ?, telefono = ?, email = ?, notas = ? WHERE id = ?";

        $stmt = $this->connection->prepare($query);

        $nombre = htmlspecialchars(strip_tags($nombre));
        $telefono = htmlspecialchars(strip_tags($telefono));
        $email = htmlspecialchars(strip_tags($email));
        $notas = htmlspecialchars(strip_tags($notas));

        $stmt->bind_param("ssssi", $nombre, $telefono, $email, $notas, $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    /**
     * ¡NUEVO! Busca clientes cuyo email o teléfono contengan un término de búsqueda.
     * @param string $term El texto a buscar.
     * @return array La lista de clientes que coinciden.
     */
    public function searchByTerm($term)
    {
        // Añadimos '%' para que busque coincidencias parciales (ej: "456" encuentra "123456789")
        $likeTerm = "%" . $term . "%";

        $query = "SELECT id, nombre, telefono, email FROM " . $this->table_name . " WHERE telefono LIKE ? OR email LIKE ? LIMIT 10";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $likeTerm, $likeTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
