#!/bin/bash
echo "⏳ Aguardando o MySQL iniciar em $DB_HOST:$DB_PORT..."

until nc -z -v -w30 $DB_HOST $DB_PORT
do
  echo "🔁 Aguardando conexão com o banco..."
  sleep 3
done

echo "✅ Banco disponível! Iniciando Apache..."
exec apache2-foreground
