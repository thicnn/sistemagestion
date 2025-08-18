<?php

class Client
{
    private $connection;
    private $table_name = "clientes";

    public function __construct($db_connection)
    {
        $this->connection = $db_connection;
    }

    public function findAll()
    {
        $query = "SELECT id, nombre, telefono, email, notas FROM " . $this->table_name . " ORDER BY nombre ASC";
        $result = $this->connection->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($nombre, $telefono, $email, $notas)
    {
        $query = "INSERT INTO " . $this->table_name . " (nombre, telefono, email, notas) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $nombre = htmlspecialchars(strip_tags($nombre));
        $telefono = htmlspecialchars(strip_tags($telefono));
        $email = htmlspecialchars(strip_tags($email));
        $notas = htmlspecialchars(strip_tags($notas));
        $stmt->bind_param("ssss", $nombre, $telefono, $email, $notas);
        return $stmt->execute();
    }

    public function findById($id)
    {
        $query = "SELECT id, nombre, telefono, email, notas FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id, $nombre, $telefono, $email, $notas)
    {
        $query = "UPDATE " . $this->table_name . " SET nombre = ?, telefono = ?, email = ?, notas = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $nombre = htmlspecialchars(strip_tags($nombre));
        $telefono = htmlspecialchars(strip_tags($telefono));
        $email = htmlspecialchars(strip_tags($email));
        $notas = htmlspecialchars(strip_tags($notas));
        $stmt->bind_param("ssssi", $nombre, $telefono, $email, $notas, $id);
        return $stmt->execute();
    }

    public function searchByTerm($term)
    {
        $likeTerm = "%" . $term . "%";
        $query = "SELECT id, nombre, telefono, email FROM " . $this->table_name . " WHERE telefono LIKE ? OR email LIKE ? LIMIT 10";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $likeTerm, $likeTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    /**
 * ¡NUEVO! Elimina un cliente de la base de datos por su ID.
 */
public function delete($id) {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
}