<?php
include __DIR__ . '/../PHP/verifica_login.php';
include_once '../BD/conexao.php';

// Consulta: Chaves disponíveis
$sql = "SELECT * FROM chave WHERE status = 'Disponivel'";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$chavesDisponiveis = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Consulta: Usuários (solicitantes)
$usuarios = [];
if (isset($_SESSION['cpf'])) {
    $sqlUsuarios = "SELECT * FROM usuario";
    $stmtUsuarios = $conexao->prepare($sqlUsuarios);
    $stmtUsuarios->execute();
    $usuarios = $stmtUsuarios->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Chave Mestra</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../IMG/cmpage.png" type="image/png">
  <link rel="stylesheet" href="../css/index.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<header class="bg-white shadow">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
    <a href="index.html">
      <img src="../IMG/CM (5).png" alt="Logo" class="h-20 w-auto" />
    </a>
    <input type="checkbox" id="menu-toggle" class="hidden peer" />
    <label for="menu-toggle" class="cursor-pointer md:hidden block">
      <div class="space-y-1.5">
        <span class="block w-6 h-0.5 bg-gray-800"></span>
        <span class="block w-6 h-0.5 bg-gray-800"></span>
        <span class="block w-6 h-0.5 bg-gray-800"></span>
      </div>
    </label>
    <nav class="absolute top-full left-0 w-full bg-white shadow-md hidden peer-checked:flex 
                flex-col space-y-2 px-6 py-4 md:static md:w-auto md:bg-transparent md:shadow-none 
                md:flex md:flex-row md:space-x-6 md:space-y-0 md:items-center md:px-0 md:py-0">
      <a href="contato.php" class="hover:bg-blue-500 hover:text-white px-4 py-2 rounded">Contato</a>
      <a href="login.php" class="hover:bg-blue-500 hover:text-white px-4 py-2 rounded">Login</a>
      <a href="chaves.php" class="hover:bg-blue-500 hover:text-white px-4 py-2 rounded">Chaves</a>
      <a href="registro.php" class="hover:bg-blue-500 hover:text-white px-4 py-2 rounded">Registro</a>
    </nav>
  </div>
</header>

<main class="p-6">
  <!-- Formulário de Retirada -->
  <div class="container-tab table-desktop">
    <h2 class="text-xl mb-4">Retirada de Chave</h2>
    <form action="../PHP/registro_chave.php" method="POST">
      <input type="hidden" name="acao" value="retirada">
      <table class="min-w-full border text-center">
        <thead>
          <tr class="bg-gray-100">
            <th class="border px-4 py-2">Chave</th>
            <th class="border px-4 py-2">Usuário</th>
            <th class="border px-4 py-2">Confirmar</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border px-4 py-2">
              <select name="chave" class="border p-2 w-full" required>
                <option value="">-- Selecione --</option>
                <?php foreach ($chavesDisponiveis as $chave): ?>
                  <option value="<?= htmlspecialchars($chave['id_chave']) ?>">
                    <?= htmlspecialchars($chave['local']) ?> (<?= htmlspecialchars($chave['predio']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </td>
            <td class="border px-4 py-2">
              <select name="usuario" class="border p-2 w-full" required>
                <option value="">-- Selecione --</option>
                <?php foreach ($usuarios as $usuario): ?>
                  <option value="<?= htmlspecialchars($usuario['cpf']) ?>">
                    <?= htmlspecialchars($usuario['nome']) ?> (<?= htmlspecialchars($usuario['cpf']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </td>
            <td class="border px-4 py-2">
              <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Confirmar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>

  <!-- Formulário de Devolução -->
  <div class="container-tab table-desktop mt-10">
    <h2 class="text-xl mb-4">Devolução de Chave</h2>
    <form action="../PHP/registro_chave.php" method="POST">
      <input type="hidden" name="acao" value="devolucao">
      <table class="min-w-full border text-center">
        <thead>
          <tr class="bg-gray-100">
            <th class="border px-4 py-2">Usuário</th>
            <th class="border px-4 py-2">Confirmar</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border px-4 py-2">
              <select name="usuario" class="border p-2 w-full" required>
                <option value="">-- Selecione --</option>
                <?php foreach ($usuarios as $usuario): ?>
                  <option value="<?= htmlspecialchars($usuario['cpf']) ?>">
                    <?= htmlspecialchars($usuario['nome']) ?> (<?= htmlspecialchars($usuario['cpf']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </td>
            <td class="border px-4 py-2">
              <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Confirmar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</main>

<footer class="footer mt-10 bg-gray-100 p-4 text-center">
  <p>&copy; 2025 Chave Mestra | <a href="contato.html" class="text-blue-500">Contato</a></p>
</footer>

</body>
</html>
