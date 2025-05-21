#!/bin/bash
set -e

# Diagnóstico
echo "DB_HOST = ${DB_HOST:-não definido}"
echo "DB_PORT = ${DB_PORT:-não definido}"

if [ -z "$DB_HOST" ] || [ -z "$DB_PORT" ]; then
  echo "❌ Variáveis DB_HOST e/ou DB_PORT não definidas. Corrija no docker-compose ou EasyPanel."
  exit 1
fi

echo "⏳ Aguardando o MySQL iniciar em $DB_HOST:$DB_PORT..."

until nc -z -v -w30 "$DB_HOST" "$DB_PORT"; do
  echo "🔁 Aguardando conexão com o banco..."
  sleep 3
done

echo "✅ Banco disponível! Iniciando Apache..."

if [ -f "composer.json" ]; then
  echo "📦 Instalando dependências do Composer..."
  composer install --no-interaction --prefer-dist
fi

exec apache2-foreground
