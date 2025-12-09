<?php
// ---- CONEXÃO ----
$pdo = new PDO("mysql:host=localhost;dbname=loja_recuperacao", "root", "");

// ---- EXCLUIR ----
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->query("DELETE FROM produtos WHERE id = $id");
    header("Location: loja.php");
}

// ---- SE ESTIVER EDITANDO, BUSCA OS DADOS ----
$editando = false;
$produtoEdit = null;

if (isset($_GET['edit'])) {
    $editando = true;
    $id = $_GET['edit'];
    $produtoEdit = $pdo->query("SELECT * FROM produtos WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
}

// ---- SALVAR (NOVO OU UPDATE) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $descricao = $_POST['descricao'];

    if (isset($_POST['id']) && $_POST['id'] !== "") {
        // UPDATE
        $id = $_POST['id'];
        $sql = $pdo->prepare("UPDATE produtos SET nome=?, categoria=?, preco=?, quantidade=?, descricao=? WHERE id=?");
        $sql->execute([$nome, $categoria, $preco, $quantidade, $descricao, $id]);
        echo "<script>alert('Produto atualizado!'); window.location='loja.php';</script>";
    } else {
        // INSERT
        $sql = $pdo->prepare("INSERT INTO produtos (nome, categoria, preco, quantidade, descricao) VALUES (?, ?, ?, ?, ?)");
        $sql->execute([$nome, $categoria, $preco, $quantidade, $descricao]);
        echo "<script>alert('Produto cadastrado!'); window.location='loja.php';</script>";
    }
}

// ---- LISTAR ----
$produtos = $pdo->query("SELECT * FROM produtos")->fetchAll(PDO::FETCH_ASSOC);
?>

<!----------------- HTML ----------------->

<h2><?= $editando ? "✏ Editar Produto" : "➕ Cadastrar Produto" ?></h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= $editando ? $produtoEdit['id'] : '' ?>">

    Nome: <input type="text" name="nome" value="<?= $editando ? $produtoEdit['nome'] : '' ?>"><br><br>
    Categoria: <input type="text" name="categoria" value="<?= $editando ? $produtoEdit['categoria'] : '' ?>"><br><br>
    Preço: <input type="number" step="0.01" name="preco" value="<?= $editando ? $produtoEdit['preco'] : '' ?>"><br><br>
    Quantidade: <input type="number" name="quantidade" value="<?= $editando ? $produtoEdit['quantidade'] : '' ?>"><br><br>

    Descrição:<br>
    <textarea name="descricao"><?= $editando ? $produtoEdit['descricao'] : '' ?></textarea><br><br>

    <button type="submit"><?= $editando ? "Salvar Alterações" : "Cadastrar" ?></button>
</form>

<br><hr><br>

<h2>📦 Produtos Cadastrados</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Categoria</th>
        <th>Preço</th>
        <th>Qtd</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($produtos as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= $p['nome'] ?></td>
        <td><?= $p['categoria'] ?></td>
        <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
        <td><?= $p['quantidade'] ?></td>
        <td>
            <a href="loja.php?edit=<?= $p['id'] ?>">✏ Editar</a> |
            <a href="loja.php?delete=<?= $p['id'] ?>" onclick="return confirm('Excluir este produto?')">🗑 Excluir</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>







