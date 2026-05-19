<?php
session_start();
// Se já estiver logado, vai direto para o dashboard
if(isset($_SESSION["usuario"])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Distribuição - Início</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
  <style>
    .hero {
      min-height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
      color: #333;
      background: linear-gradient(
        rgba(255, 255, 255, 0.9),   /* branco translúcido no topo */
        rgba(240, 240, 240, 0.9)    /* cinza claro translúcido embaixo */
      );
      padding: 60px 20px;
    }
    .hero h1 {
      font-size: 2.5rem;
      font-weight: bold;
      color: #007bff;
      margin-bottom: 20px;
    }
    .hero p {
      font-size: 1.2rem;
      max-width: 700px;
      margin: auto;
      margin-bottom: 30px;
    }
    .hero .btn {
      margin: 10px;
      padding: 12px 25px;
      font-size: 1.1rem;
      border-radius: 8px;
    }
    .logo-index {
      width: 160px;
      margin-bottom: 20px;
    }
    .story {
      background: rgba(255,255,255,0.85);
      color: #333;
      padding: 40px;
      border-radius: 12px;
      max-width: 900px;
      margin: 40px auto;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .story h2 {
      color: #007bff;
      font-weight: bold;
      margin-bottom: 20px;
    }
    #map {
      height: 400px;
      width: 100%;
      border-radius: 12px;
      margin-top: 20px;
    }
  </style>
</head>
<body>

<div class="hero">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-index">
  <h1>Bem-vindo ao Sistema de Distribuição</h1>
  <p>Conectando doadores, beneficiários, voluntários e depósitos para combater a fome de forma organizada e transparente.</p>
  <div>
    <a href="login.php" class="btn btn-primary">Entrar</a>
    <a href="cadastro.php" class="btn btn-outline-primary">Cadastrar</a>
  </div>
</div>

<div class="story">
  <h2>📖 Nossa História</h2>
  <p>
    O projeto <strong>Distribuição</strong> nasceu da necessidade de aproximar quem tem condições de doar alimentos 
    e quem mais precisa recebê-los. Em muitas comunidades, a solidariedade existe, mas faltava uma forma 
    organizada de conectar doadores, beneficiários, voluntários e depósitos.
  </p>
  <p>
    Com a tecnologia, conseguimos criar um sistema simples e acessível que permite:
    <ul>
      <li>📦 Doadores registrarem suas contribuições;</li>
      <li>🛒 Beneficiários solicitarem alimentos de forma digna;</li>
      <li>🚚 Voluntários acompanharem e registrarem entregas;</li>
      <li>🏢 Depósitos gerenciarem o estoque com transparência.</li>
    </ul>
  </p>
  <p>
    Nosso objetivo é <strong>combater a fome</strong> e promover a solidariedade, garantindo que cada alimento 
    chegue a quem realmente precisa. Este é apenas o começo da nossa jornada!
  </p>

  <h2 class="mt-4">🗺️ Pontos de Coleta e Entrega</h2>
  <div id="map"></div>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
  var map = L.map('map').setView([-23.5505, -46.6333], 12);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  L.marker([-23.5505, -46.6333]).addTo(map)
    .bindPopup("📦 Ponto de Coleta - Centro");

  L.marker([-23.5595, -46.6350]).addTo(map)
    .bindPopup("🚚 Ponto de Entrega - Zona Sul");

  L.marker([-23.5400, -46.6200]).addTo(map)
    .bindPopup("🏢 Depósito - Zona Leste");
</script>

</body>
</html>
