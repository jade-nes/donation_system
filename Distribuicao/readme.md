# 🍎 Sistema de Distribuição de Alimentos

Projeto desenvolvido para conectar **doadores**, **beneficiários**, **voluntários** e **depósitos** em uma rede solidária contra a fome.  
O sistema organiza doações, solicitações, entregas e gestão de estoque de forma simples e transparente.

---

## 🚀 Funcionalidades

- 📦 **Doadores**: registrar e acompanhar doações.
- 🛒 **Beneficiários**: solicitar alimentos disponíveis.
- 🚚 **Voluntários**: registrar e acompanhar entregas.
- 🏢 **Depósitos**: gerenciar estoque de alimentos.
- ⚙️ **Usuários**: editar cadastro e atualizar informações pessoais.
- 🗺️ **Mapa interativo**: visualizar pontos de coleta e entrega.

---

## 🛠️ Tecnologias Utilizadas

- **PHP** (backend e páginas dinâmicas)
- **MySQL** (banco de dados)
- **Bootstrap 5** (estilo responsivo)
- **Leaflet.js** (mapa interativo)
- **HTML5 / CSS3**

---

## 📂 Estrutura do Projeto

distribuicao/
├── public/
│    ├── index.php
│    ├── dashboard.php
│    ├── login.php
│    ├── cadastro.php
│    ├── doador.php
│    ├── beneficiario.php
│    ├── voluntario.php
│    ├── deposito.php
│    └── editar.php
├── config/
│    └── db.php
├── assets/
│    ├── style.css
│    └── logo.png
├── database/
│    └── distribucao.sql
└── README.md

Código

---

## ⚙️ Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/seuusuario/distribuicao-alimentos.git
Configure o banco de dados:

Crie um banco MySQL.

Importe o arquivo database/distribucao.sql:

bash
mysql -u usuario -p nome_do_banco < database/distribucao.sql
Configure o arquivo config/db.php com suas credenciais:

php
<?php
$conn = new mysqli("localhost", "usuario", "senha", "nome_do_banco");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
Inicie o servidor local (XAMPP, WAMP ou PHP embutido):

bash
php -S localhost:8000 -t public
Acesse no navegador:

Código
http://localhost/distribuicao/public/index.php