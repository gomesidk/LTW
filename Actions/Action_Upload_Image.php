<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/userClass.php');
require_once(__DIR__ . '/../Utils/Session.php');

$session = new Session();

if (!$session->getId()) {
    header('Location: ../Pages/login.php');
    exit;
}

$db = getDatabaseConnection();
$userId = $session->getId();
$user = User::getUser($db, (int)$userId);

// Cria pastas se não existirem
if (!is_dir(__DIR__ . '/images')) mkdir(__DIR__ . '/images');
if (!is_dir(__DIR__ . '/images/originals')) mkdir(__DIR__ . '/images/originals');
if (!is_dir(__DIR__ . '/images/thumbs_small')) mkdir(__DIR__ . '/images/thumbs_small');
if (!is_dir(__DIR__ . '/images/thumbs_medium')) mkdir(__DIR__ . '/images/thumbs_medium');

// Verifica se o arquivo foi enviado
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    die('Erro no upload da imagem.');
}

$tempFileName = $_FILES['image']['tmp_name'];

// Tenta criar a imagem a partir do arquivo
$original = @imagecreatefromjpeg($tempFileName);
if (!$original) $original = @imagecreatefrompng($tempFileName);
if (!$original) $original = @imagecreatefromgif($tempFileName);
if (!$original) die('Formato de imagem desconhecido!');

// Insere título da imagem no banco na tabela "images"
$stmt = $db->prepare("INSERT INTO images (title) VALUES (?)");
$stmt->execute([$_POST['title'] ?? '']);

// Pega o último ID inserido
$id = $db->lastInsertId();

// Atualiza o id da imagem no usuário
$stmt = $db->prepare("UPDATE User SET profile_picture_id = ? WHERE id = ?");
$stmt->execute([$id, $userId]);

// Define caminhos absolutos para salvar as imagens
$baseDir = __DIR__ . '/images';
$originalFileName = "$baseDir/originals/$id.jpg";
$smallFileName = "$baseDir/thumbs_small/$id.jpg";
$mediumFileName = "$baseDir/thumbs_medium/$id.jpg";

$width = imagesx($original);
$height = imagesy($original);
$square = min($width, $height);

// Salva a imagem original em jpeg
imagejpeg($original, $originalFileName);

// Cria miniatura quadrada pequena (200x200)
$small = imagecreatetruecolor(200, 200);
imagecopyresized(
    $small, $original,
    0, 0,
    ($width > $square) ? ($width - $square) / 2 : 0,
    ($height > $square) ? ($height - $square) / 2 : 0,
    200, 200,
    $square, $square
);
imagejpeg($small, $smallFileName);

// Ajusta tamanho médio (max width 400px)
$mediumWidth = $width;
$mediumHeight = $height;
if ($mediumWidth > 400) {
    $mediumHeight = (int)$mediumHeight * (int)(400 / $mediumWidth);
    $mediumWidth = 400;
}

// Cria imagem média redimensionada
$medium = imagecreatetruecolor($mediumWidth, $mediumHeight);
imagecopyresized($medium, $original, 0, 0, 0, 0, $mediumWidth, $mediumHeight, $width, $height);
imagejpeg($medium, $mediumFileName);

// Redireciona de volta para editar perfil
header("Location: ../Pages/edit_profile.php");
exit;
?>
