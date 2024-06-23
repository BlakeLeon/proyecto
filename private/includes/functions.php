<?php
require 'db.php';

/**
 * Función para redirigir a una URL específica
 * 
 * @param string $url La URL a la que se redirigirá
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Función para sanitizar datos de entrada
 * 
 * @param string $data El dato a sanitizar
 * @return string El dato sanitizado
 */
function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

/**
 * Función para obtener datos de usuario por email
 * 
 * @param string $email El email del usuario
 * @return array|null Los datos del usuario o null si no se encuentra
 */
function getUserByEmail($email) {
    global $conn;
    $email = sanitizeInput($email);
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

/**
 * Función para verificar si un usuario está autenticado
 * 
 * @return bool True si el usuario está autenticado, false si no
 */
function isAuthenticated() {
    return isset($_SESSION['usuario']);
}

/**
 * Función para obtener todos los productos
 * 
 * @return array Un array de productos
 */
function getAllProducts() {
    global $conn;
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);
    $products = [];
    
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    return $products;
}

/**
 * Función para obtener un producto por su ID
 * 
 * @param int $id El ID del producto
 * @return array|null Los datos del producto o null si no se encuentra
 */
function getProductById($id) {
    global $conn;
    $id = sanitizeInput($id);
    $sql = "SELECT * FROM productos WHERE id='$id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

/**
 * Función para verificar si un usuario es administrador
 * 
 * @return bool True si el usuario es administrador, false si no
 */
function isAdmin() {
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 'admin';
}

// Función para buscar productos por término
function searchProducts($searchTerm) {
    global $db; // Variable de conexión a la base de datos definida en db.php
    $searchTerm = mysqli_real_escape_string($db, $searchTerm);
    $query = "SELECT * FROM productos WHERE nombre LIKE '%$searchTerm%' OR descripcion LIKE '%$searchTerm%'";
    $result = mysqli_query($db, $query);
    $products = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
    return $products;
}

/**
 * Función para registrar un nuevo usuario
 * 
 * @param string $nombre Nombre del usuario
 * @param string $email Email del usuario
 * @param string $password Contraseña del usuario (ya debe estar hasheada)
 * @return bool True si se registró correctamente, false si hubo algún error
 */
function registerUser($nombre, $email, $password) {
    global $conn;
    
    // Sanitizar los datos de entrada
    $nombre = sanitizeInput($nombre);
    $email = sanitizeInput($email);
    $password = sanitizeInput($password);

    // Consulta SQL para insertar el usuario
    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password')";

    // Ejecutar la consulta y verificar si fue exitosa
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}
function addProduct($nombre, $descripcion, $precio, $imagen) {
    global $conn;
    $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $imagen);
    $stmt->execute();
    $stmt->close();
}

// En functions.php
function deleteProduct($product_id) {
    global $conn; // Asegúrate de tener $conn definido y disponible aquí

    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        return true; // Éxito al borrar el producto
    } else {
        return false; // Error al borrar el producto
    }
}


// Función para actualizar un producto en la base de datos
function updateProduct($product_id, $nombre, $descripcion, $precio, $imagen) {
    global $conn;

    // Preparar la consulta SQL
    $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, imagen=? WHERE id=?";

    // Preparar la sentencia
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen, $product_id);

        // Ejecutar la sentencia
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Error al ejecutar la consulta: " . $stmt->error;
        }
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }

    return false;
}


// Función para obtener todos los usuarios
function getAllUsers() {
    global $db; // Conexión a la base de datos

    $query = "SELECT * FROM usuarios";
    $result = mysqli_query($db, $query);

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    return $users;
}

// Función para obtener un usuario por ID
function getUserById($user_id) {
    global $db; // Conexión a la base de datos

    $user_id = mysqli_real_escape_string($db, $user_id);
    $query = "SELECT * FROM usuarios WHERE id = $user_id";
    $result = mysqli_query($db, $query);

    return mysqli_fetch_assoc($result);
}

// Función para actualizar un usuario
function updateUser($user_id, $nombre, $email, $es_admin) {
    global $db; // Asegúrate de tener acceso a tu conexión $db aquí
    
    // Sanitizar y escapar las entradas para evitar SQL Injection
    $user_id = mysqli_real_escape_string($db, $user_id);
    $nombre = mysqli_real_escape_string($db, $nombre);
    $email = mysqli_real_escape_string($db, $email);
    $es_admin = (int) $es_admin; // Asegúrate de que sea un entero (0 o 1)
    
    // Query para actualizar el usuario
    $query = "UPDATE usuarios SET nombre = '$nombre', email = '$email', es_admin = $es_admin WHERE id = $user_id";
    
    // Ejecutar la consulta
    if (mysqli_query($db, $query)) {
        return true; // Éxito al actualizar
    } else {
        return false; // Error al ejecutar la consulta
    }
}

// Función para eliminar un usuario
function deleteUser($user_id) {
    global $db; // Conexión a la base de datos

    $user_id = mysqli_real_escape_string($db, $user_id);
    $query = "DELETE FROM usuarios WHERE id = $user_id";
    return mysqli_query($db, $query);
}


function updateUserProfile($id, $nombre, $email, $direccion, $foto) {
    global $db; // Conexión a la base de datos

    // Escapar datos para prevenir inyección SQL
    $id = mysqli_real_escape_string($db, $id);
    $nombre = mysqli_real_escape_string($db, $nombre);
    $email = mysqli_real_escape_string($db, $email);
    $direccion = mysqli_real_escape_string($db, $direccion);
    $foto = mysqli_real_escape_string($db, $foto);

    $query = "UPDATE usuarios SET nombre = '$nombre', email = '$email', direccion = '$direccion', foto = '$foto' WHERE id = $id";
    return mysqli_query($db, $query);
}



?>


