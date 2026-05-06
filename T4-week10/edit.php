<?php
require_once 'config/database.php';

$pesan = '';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);

$data = $stmt->fetch();

if (!$data) {

    header("Location: index.php");
    exit;

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul         = trim($_POST['judul'] ?? '');
    $pengarang     = trim($_POST['pengarang'] ?? '');
    $penerbit      = trim($_POST['penerbit'] ?? '');
    $tahun_terbit  = trim($_POST['tahun_terbit'] ?? '');
    $stok          = trim($_POST['stok'] ?? '');

    if (
        !empty($judul) &&
        !empty($pengarang) &&
        !empty($penerbit) &&
        !empty($tahun_terbit) &&
        $stok !== ''
    ) {

        $stmt = $pdo->prepare("
            UPDATE buku
            SET
                judul = :judul,
                pengarang = :pengarang,
                penerbit = :penerbit,
                tahun_terbit = :tahun_terbit,
                stok = :stok
            WHERE id = :id
        ");

        $stmt->execute([
            ':judul'         => $judul,
            ':pengarang'     => $pengarang,
            ':penerbit'      => $penerbit,
            ':tahun_terbit'  => $tahun_terbit,
            ':stok'          => $stok,
            ':id'            => $id
        ]);

        header("Location: index.php?pesan=edit_sukses");
        exit;

    } else {

        $pesan = "Semua field wajib diisi!";

    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Buku</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5" style="max-width: 700px;">

    <h2>🖊️ Edit Buku</h2>

    <?php if ($pesan): ?>
        <div class="alert alert-danger"><?= $pesan ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Judul Buku</label>
            <input
                type="text"
                name="judul"
                class="form-control"
                value="<?= htmlspecialchars($data['judul']) ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Pengarang</label>
            <input
                type="text"
                name="pengarang"
                class="form-control"
                value="<?= htmlspecialchars($data['pengarang']) ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Penerbit</label>
            <input
                type="text"
                name="penerbit"
                class="form-control"
                value="<?= htmlspecialchars($data['penerbit']) ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Tahun Terbit</label>
            <input
                type="number"
                name="tahun_terbit"
                class="form-control"
                value="<?= htmlspecialchars($data['tahun_terbit']) ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Stok Buku</label>
            <input
                type="number"
                name="stok"
                class="form-control"
                value="<?= htmlspecialchars($data['stok']) ?>"
                required
            >
        </div>

        <button type="submit" class="btn btn-warning">
            Update
        </button>

        <a href="index.php" class="btn btn-secondary">
            ← Kembali
        </a>

    </form>

</div>

</body>
</html>