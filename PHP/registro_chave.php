<?php
include __DIR__ . '/../PHP/verifica_login.php';
include_once '../BD/conexao.php';

$cpf_adm = $_SESSION['cpf'] ?? null;
$dataAtual = date('Y-m-d');
$horaAtual = date('Y-m-d H:i:s');

// ---------------------- EMPRÉSTIMO ----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'retirada') {
    $id_chave = $_POST['id_chave'] ?? null;
    $cpf_solicitante = $_POST['cpf_solicitante'] ?? null;

    if ($id_chave && $cpf_solicitante && $cpf_adm) {
        $sqlInsert = "INSERT INTO emprestimo (
            data_reserva, data_inicio_reserva, data_fim_reserva,
            hora_data_retirada, hora_data_devolucao,
            id_chave, cpf_solicitante, cpf_adm
        ) VALUES (?, ?, ?, ?, NULL, ?, ?, ?)";

        $stmtInsert = $conexao->prepare($sqlInsert);
        $stmtInsert->bind_param(
            'sssssss',
            $dataAtual, $dataAtual, $dataAtual, $horaAtual,
            $id_chave, $cpf_solicitante, $cpf_adm
        );

        if ($stmtInsert->execute()) {
            $update = $conexao->prepare("UPDATE chave SET status = 'Ocupada' WHERE id_chave = ?");
            $update->bind_param('i', $id_chave);
            $update->execute();

            echo "<script>alert('Empréstimo registrado com sucesso!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Erro ao registrar o empréstimo: " . $stmtInsert->error . "');</script>";
        }
    } else {
        echo "<script>alert('Preencha todos os campos.');</script>";
    }
}

// ---------------------- DEVOLUÇÃO ----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'devolucao') {
    $cpf_solicitante = $_POST['cpf_solicitante_devolucao'] ?? null;
    $dataHoraAtual = date('Y-m-d H:i:s');

    if ($cpf_solicitante) {
        $sqlBuscar = "SELECT id_emprestimo, id_chave FROM emprestimo 
                      WHERE cpf_solicitante = ? AND hora_data_devolucao IS NULL 
                      ORDER BY id_emprestimo DESC LIMIT 1";

        $stmtBuscar = $conexao->prepare($sqlBuscar);
        $stmtBuscar->bind_param('s', $cpf_solicitante);
        $stmtBuscar->execute();
        $resultado = $stmtBuscar->get_result();

        if ($resultado->num_rows > 0) {
            $emprestimo = $resultado->fetch_assoc();
            $id_emprestimo = $emprestimo['id_emprestimo'];
            $id_chave = $emprestimo['id_chave'];

            $sqlDevolver = "UPDATE emprestimo SET hora_data_devolucao = ? WHERE id_emprestimo = ?";
            $stmtDevolver = $conexao->prepare($sqlDevolver);
            $stmtDevolver->bind_param('si', $dataHoraAtual, $id_emprestimo);
            $stmtDevolver->execute();

            $sqlAtualizaChave = "UPDATE chave SET status = 'Disponivel' WHERE id_chave = ?";
            $stmtAtualizaChave = $conexao->prepare($sqlAtualizaChave);
            $stmtAtualizaChave->bind_param('i', $id_chave);
            $stmtAtualizaChave->execute();

            echo "<script>alert('Devolução registrada com sucesso!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Nenhum empréstimo ativo encontrado para este usuário.');</script>";
        }
    } else {
        echo "<script>alert('Selecione um usuário para devolução.');</script>";
    }
}

// ---------------------- CONSULTAS ----------------------
$sql = "SELECT * FROM chave WHERE status = 'Disponivel'";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$chavesDisponiveis = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$usuarios = [];
if (isset($_SESSION['cpf']) && isset($_COOKIE['usuario_logado'])) {
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
    <a href="../pages/index.html">
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
      <a href="../Pages/contato.php" class="hover:bg-blue-500 hover:text-white px-4 py-2 rounded">Contato</a>
      <a href="../PHP/login.php" class="hover:bg-blue-500 hover:text-white px-4 py-2 rounded">Login</a>
      <a href="../Pages/chaves.php" class="hover:bg-blue-500 hover:text-white px-4 py-2 rounded">Chaves</a>
      <a href="../Pages/registro.php" class="hover:bg-blue-500 hover:text-white px-4 py-2 rounded">Registro</a>
    </nav>
  </div>
</header>

<main class="p-6">
  <!-- Formulário de retirada -->
  <form method="POST" action="../PHP/index.php">
      <input type="hidden" name="acao" value="retirada">
    <div class="container-tab table-desktop">
      <h2 class="text-xl mb-4">Gerenciamento de Chaves <br><em>(Somente Funcionários)</em></h2>
      <div class="table-responsive">
        <table class="min-w-full border text-center">
          <thead>
            <tr class="bg-gray-100">
              <th class="border px-4 py-2">Chave</th>
              <th class="border px-4 py-2">Categoria</th>
              <th class="border px-4 py-2">Retirada</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="border px-4 py-2">
                <select name="id_chave" class="border p-2 w-full" required>
                  <option value="">-- Selecione --</option>
                  <?php foreach ($chavesDisponiveis as $chave): ?>
                    <option value="<?= htmlspecialchars($chave['id_chave']) ?>">
                      <?= htmlspecialchars($chave['local']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td class="border px-4 py-2">
                <select name="categoria" class="border p-2 w-full" required>
                  <option value="aluno">Aluno</option>
                  <option value="funcionario">Funcionário</option>
                </select>
              </td>
              <td class="border px-4 py-2">
                <select name="cpf_solicitante" class="border p-2 w-full" required>
                  <option value="">-- Selecione --</option>
                  <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= htmlspecialchars($usuario['cpf']) ?>">
                      <?= htmlspecialchars($usuario['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" name="confirmar_retirada" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">
                  Confirmar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </form>

  <!-- Formulário de devolução -->
  <form method="POST" action="../PHP/index.php">
      <input type="hidden" name="acao" value="retirada">
    <div class="container-tab table-desktop mt-10">
      <div class="table-responsive">
        <table class="min-w-full border text-center">
          <thead>
            <tr class="bg-gray-100">
              <th class="border px-4 py-2">Devolução</th>
              <th class="border px-4 py-2">Confirmação</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="border px-4 py-2">
                <select name="cpf_solicitante_devolucao" class="border p-2 w-full" required>
                  <option value="">-- Selecione --</option>
                  <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= htmlspecialchars($usuario['cpf']) ?>">
                      <?= htmlspecialchars($usuario['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td class="border px-4 py-2">
                <button type="submit" name="confirmar_devolucao" class="bg-green-500 text-white px-4 py-2 rounded">
                  Confirmar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </form>
</main>

<footer class="footer mt-10 bg-gray-100 p-4 text-center">
  <p>&copy; 2025 Chave Mestra | <a href="../Pages/contato.html" class="text-blue-500">Contato</a></p>
</footer>

</body>
</html>
