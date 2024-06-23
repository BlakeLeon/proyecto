<?php
session_start();
include '../../private/includes/db.php';
include '../../private/includes/functions.php';

$searchTerm = '';
$products = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = sanitizeInput($_POST['search_term']);
    $products = searchProducts($searchTerm);
}

function searchProducts($term) {
    global $conn;
    $term = "%{$term}%";
    $sql = "SELECT * FROM productos WHERE nombre LIKE ? OR descripcion LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $term, $term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    return $products;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Productos</title>
    <link rel="stylesheet" href="../includes/style.css"> <!-- Asumiendo que tienes un archivo CSS -->
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <h1>Búsqueda de Productos</h1>
    <form method="POST" action="search.php">
        <input type="text" name="search_term" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($searchTerm); ?>" required>
        <button type="submit">Buscar</button>
    </form>
    
    <div class="product-list">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <h2><?php echo $product['nombre']; ?></h2>
                    <p><?php echo $product['descripcion']; ?></p>
                    <p>Precio: $<?php echo $product['precio']; ?></p>
                    <a href="details.php?id=<?php echo $product['id']; ?>">Ver detalles</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron productos.</p>
        <?php endif; ?>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
