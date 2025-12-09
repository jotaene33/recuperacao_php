<?php
$host = "localhost";
$dbName = "loja_recuperacao";
$user = "root";
$pass = "";

// Conectar ao MySQL
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

// Excluir produto se for pedido
if (isset($_GET['excluir'])) {
    $idExcluir = intval($_GET['excluir']);
    $pdo->prepare("DELETE FROM produtos WHERE id = ?")->execute([$idExcluir]);
    echo "<p>Produto excluído com sucesso!</p>";
}

// Inserir produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = $pdo->prepare("INSERT INTO produtos (nome, categoria, preco, quantidade, descricao) VALUES (?, ?, ?, ?, ?)");
    $sql->execute([
        $_POST['nome'],
        $_POST['categoria'],
        $_POST['preco'],
        $_POST['quantidade'],
        $_POST['descricao']
    ]);
    echo "<p>Produto cadastrado com sucesso!</p>";
}

// Buscar todos os produtos
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Cadastro de Produto</h2>
<form method="POST">
    Nome:<br><input type="text" name="nome" required><br>
    Categoria:<br><input type="text" name="categoria" required><br>
    Preço:<br><input type="number" step="0.01" name="preco" required><br>
    Quantidade:<br><input type="number" name="quantidade" required><br>
    Descrição:<br><textarea name="descricao" required></textarea><br>
    <button type="submit">Cadastrar</button>
</form>

<h2>Produtos Cadastrados</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Categoria</th>
        <th>Preço</th>
        <th>Quantidade</th>
        <th>Descrição</th>
        <th>Ação</th>
    </tr>
    <?php foreach($produtos as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['nome']) ?></td>
        <td><?= htmlspecialchars($p['categoria']) ?></td>
        <td><?= $p['preco'] ?></td>
        <td><?= $p['quantidade'] ?></td>
        <td><?= htmlspecialchars($p['descricao']) ?></td>
        <td><a href="?excluir=<?= $p['id'] ?>" onclick="return confirm('Tem certeza que quer excluir?')">Excluir</a></td>
    </tr>
    <?php endforeach; ?>
</table>




