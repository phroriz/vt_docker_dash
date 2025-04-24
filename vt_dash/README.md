# 🐳 vt_docker_dash

Projeto de painel PHP com suporte a Docker, MySQL e Composer.

---

## 🚀 Tecnologias usadas

- PHP 8.2 + Apache
- MySQL 8.0
- Composer (gerenciador de dependências PHP)
- Docker + Docker Compose

---

## ⚙️ Pré-requisitos

Antes de começar, você precisa ter instalado:

- [Docker](https://www.docker.com/products/docker-desktop)
- [Docker Compose](https://docs.docker.com/compose/)
- Git (opcional, para clonar o repositório)

---

## 📦 Clonando o projeto

```bash
git clone https://github.com/phroriz/vt_docker_dash.git
cd vt_docker_dash
```

---

## 🛠️ Estrutura do projeto

```text
vt_docker_dash/
├── vt_dash/                 # Código da aplicação
│   ├── public/              # DocumentRoot (index.php)
│   ├── src/                 # Controllers, Config, Models
│   ├── core/                # Router, BaseController, etc.
│   ├── composer.json        # Dependências
│   └── dashboard.sql        # Dump inicial do banco
├── docker/
│   └── php/
│       ├── Dockerfile       # PHP + Apache + Composer
│       └── entrypoint.sh    # Instala dependências na inicialização
├── docker-compose.yml       # Orquestra os containers
└── README.md
```

---

## 🐳 Como rodar com Docker

### 1. Subir os containers (PHP + MySQL)

```bash
docker compose up --build
```

---

### 2. Instalar as dependências do Composer

Se não forem instaladas automaticamente, rode:

```bash
docker exec -it phpserver composer install
```

---

## 🧪 Testando conexão com o banco

Dentro do container PHP:

```bash
docker exec -it phpserver bash
php -r "new PDO('mysql:host=db;dbname=dashboard', 'root', 'root'); echo 'Conexão OK';"
```

---

## 🧹 Comandos úteis

| Comando                         | Descrição                              |
|----------------------------------|------------------------------------------|
| `docker compose down`           | Para e remove os containers             |
| `docker compose up -d`          | Sobe em segundo plano                   |
| `docker exec -it phpserver bash`| Acessa o container PHP                  |
| `composer install`              | Instala dependências (dentro do PHP)    |

---

## 🧾 Licença

Este projeto é de uso livre para fins educacionais e internos.
