<?php include __DIR__ . '/../PHP/verifica_login.php'; ?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="CM.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/index.css">
  <link rel="icon" type="image/png" href="../IMG/cmpage.png">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Registro de Chaves</title>
  <script defer>
    function filtrarChaves() {
      const input = document.getElementById('busca').value.toLowerCase();
      const linhas = document.querySelectorAll('#tabela-chaves tbody tr');

      linhas.forEach(linha => {
        const textoLinha = linha.textContent.toLowerCase();
        linha.style.display = textoLinha.includes(input) ? '' : 'none';
      });
    }
  </script>
</head>
<body class="bg-gray-100">

<!-- Header importado -->
<main>
  <header class="relative top-0 left-0 w-full bg-white shadow z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
      <!-- Logo -->
      <a href="../pages/index.html" class="flex items-center">
        <img src="../IMG/CM (5).png" alt="Logo" class="h-20 w-auto" />
      </a>

      <!-- Menu Toggle (mobile) -->
      <input type="checkbox" id="menu-toggle" class="hidden peer" />
      <label for="menu-toggle" class="cursor-pointer md:hidden block">
        <div class="space-y-1.5">
          <span class="block w-6 h-0.5 bg-gray-800"></span>
          <span class="block w-6 h-0.5 bg-gray-800"></span>
          <span class="block w-6 h-0.5 bg-gray-800"></span>
        </div>
      </label>

      <!-- Menu feito com Tailwind -->
      <nav class="absolute top-full left-0 w-full bg-white shadow-md 
                  flex-col space-y-2 px-6 py-4
                  hidden peer-checked:flex 
                  md:static md:w-auto md:bg-transparent md:shadow-none 
                  md:flex md:flex-row md:space-x-6 md:space-y-0 md:items-center md:px-0 md:py-0">
        <a href="../Pages/contato.html" class="block text-gray-700 hover:bg-blue-500 hover:text-white px-4 py-2 rounded transition">Contato</a>
        <a href="../PHP/login.php" class="block text-gray-700 hover:bg-blue-500 hover:text-white px-4 py-2 rounded transition">Login</a>
        <a href="../Pages/chaves.php" class="block text-gray-700 hover:bg-blue-500 hover:text-white px-4 py-2 rounded transition">Chaves</a>
        <a href="../Pages/registro.html" class="block text-gray-700 hover:bg-blue-500 hover:text-white px-4 py-2 rounded transition">Registro</a>
      </nav>
    </div>
  </header>
</main>

 <!-- Conteúdo principal -->
 <div class="flex-grow">
  <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow mt-10">
    <h1 class="text-2xl font-bold mb-4">Registro de Chaves</h1>

    <input type="text" id="busca" onkeyup="filtrarChaves()" placeholder="Buscar por nome, chave, sala..."
      class="w-full p-2 border border-gray-300 rounded mb-4" />

    <div class="overflow-x-auto">
      <table id="tabela-chaves" class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-200 text-left">
          <tr>
            <th class="p-2 border">ID</th>
            <th class="p-2 border">Nome</th>
            <th class="p-2 border">Chave</th>
            <th class="p-2 border">Sala</th>
            <th class="p-2 border">Data</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:bg-gray-50">
            <td class="p-2 border">1</td>
            <td class="p-2 border">João Silva</td>
            <td class="p-2 border">CHV123</td>
            <td class="p-2 border">A101</td>
            <td class="p-2 border">2025-05-10</td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="p-2 border">2</td>
            <td class="p-2 border">Maria Oliveira</td>
            <td class="p-2 border">CHV124</td>
            <td class="p-2 border">B202</td>
            <td class="p-2 border">2025-05-11</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Footer sempre no fim -->
<body>
  <div class="page-wrapper">
    <!-- Conteúdo aqui -->
  </div>

  <footer class="footer">
    <div class="footer-container">
      <p>&copy; 2025 Chave Mestra | 
        <a href="../Pages/contato.html" class="footer-link">Contato</a>
      </p>
    </div>
  </footer>
</body>


<script>
  setTimeout(() => {
    alert('Sua sessão expirou! Faça Login Novamente!');
    window.location.href = '../PHP/login.php'; // redireciona para login
  }, <?= $tempoRestante ?>);
</script>


</body>
</html>
