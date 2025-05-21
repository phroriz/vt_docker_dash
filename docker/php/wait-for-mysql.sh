#!/bin/bash
set -e

echo "⏳ Aguardando o MySQL iniciar em $DB_HOST:$DB_PORT..."

# Loop até conseguir conectar ao MySQL
until nc -z -v -w30 "$DB_HOST" "$DB_PORT"; do
  echo "🔁 Aguardando conexão com o banco..."
  sleep 3
done

echo "✅ Banco disponível! Iniciando Apache..."

# Instala dependências do Composer, se necessário
if [ -f "composer.json" ]; then
  echo "📦 Instalando dependências do Composer..."
  composer install --no-interaction --prefer-dist
fi

# Executa o Apache em foreground
exec apache2-foreground
