#!/bin/bash

# Navega até o projeto
cd /var/www/html

# Instala as dependências se não houver vendor/
if [ ! -d "vendor" ]; then
  echo "📦 Instalando dependências do Composer..."
  composer install --no-interaction --prefer-dist
else
  echo "✅ Dependências já instaladas"
fi

# Executa o comando padrão do container (Apache)
exec "$@"
