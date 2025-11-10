#!/bin/bash
set -euo pipefail

# Entrypoint robusto para Laravel + espera por DB + installs/migrations
# Caminho esperado: /var/www/html (WORKDIR)
# Requer: default-mysql-client presente na imagem (mysql

echo "🚀 Entrypoint: inicializando container Laravel..."

cd /var/www/html || { echo "Diretório /var/www/html não encontrado"; exit 1; }

# --- Função utilitária: ler variável do .env se não estiver no env do processo
read_env_var_from_file() {
  local key="$1"
  local def="${2:-}"
  local val=""

  # Prioriza variável de ambiente já exportada
  if [ ! -z "${!key:-}" ]; then
    echo "${!key}"
    return 0
  fi

  # Se houver .env, tenta extrair
  if [ -f ".env" ]; then
    val=$(grep -m1 -E "^${key}=" .env | cut -d '=' -f2- | sed 's/^"//;s/"$//;s/\r$//')
    if [ ! -z "$val" ]; then
      echo "$val"
      return 0
    fi
  fi

  # fallback default
  echo "$def"
  return 0
}

# --- Ler configuração do DB (default sensatos)
DB_HOST=$(read_env_var_from_file "DB_HOST" "db")
DB_PORT=$(read_env_var_from_file "DB_PORT" "3306")
DB_USER=$(read_env_var_from_file "DB_USERNAME" "root")
DB_PASS=$(read_env_var_from_file "DB_PASSWORD" "")
DB_NAME=$(read_env_var_from_file "DB_DATABASE" "")

# Tempo de espera máximo (em segundos) e intervalo
MAX_WAIT_SECONDS=120
SLEEP_INTERVAL=2
elapsed=0

echo "🔎 Aguardando banco de dados em ${DB_HOST}:${DB_PORT} (usuário: ${DB_USER})"

# Função para checar MySQL via mysqladmin (se disponível); fallback para nc
db_is_up() {
  # tenta mysqladmin ping
  if command -v mysqladmin >/dev/null 2>&1; then
    if [ -z "$DB_PASS" ]; then
      mysqladmin -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USER" ping >/dev/null 2>&1 && return 0
    else
      mysqladmin -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USER" -p"$DB_PASS" ping >/dev/null 2>&1 && return 0
    fi
  fi

  # fallback: nc
  if command -v nc >/dev/null 2>&1; then
    nc -z "$DB_HOST" "$DB_PORT" >/dev/null 2>&1 && return 0
  fi

  return 1
}

# Loop de espera
while ! db_is_up; do
  if [ "$elapsed" -ge "$MAX_WAIT_SECONDS" ]; then
    echo "⏱ Timeout: banco de dados não respondeu após ${MAX_WAIT_SECONDS}s."
    break
  fi
  echo "⏳ Banco indisponível — esperando ${SLEEP_INTERVAL}s (já esperado ${elapsed}s)..."
  sleep "$SLEEP_INTERVAL"
  elapsed=$((elapsed + SLEEP_INTERVAL))
done

if db_is_up; then
  echo "✅ Banco de dados acessível (após ${elapsed}s)."
else
  echo "⚠️  Banco de dados ainda indisponível — o script continuará, mas migrations poderão falhar."
fi

# --- 1) Composer install se necessário
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor 2>/dev/null || true)" ]; then
  echo "📦 vendor ausente ou vazio — executando composer install..."
  # permitir retorno mesmo se falhar em ambientes sem composer.json
  composer install --no-interaction --prefer-dist --optimize-autoloader || echo "⚠️ composer install falhou ou não aplicável (verifique composer.json)."
else
  echo "📦 vendor presente — pulando composer install."
fi

# --- 2) .env e APP_KEY
if [ ! -f ".env" ]; then
  if [ -f ".env.example" ]; then
    echo "⚙️  .env não encontrado — copiando .env.example..."
    cp .env.example .env
  else
    echo "⚠️  .env e .env.example ausentes — continue com cuidado."
  fi
fi

# Gera APP_KEY se estiver vazio ou sem base64:
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
  echo "🔑 Gerando APP_KEY..."
  php artisan key:generate --force || echo "⚠️ key:generate falhou."
else
  echo "🔑 APP_KEY já presente."
fi

# --- 3) Executar migrations com tentativas (caso DB ainda esteja inicializando)
MIG_ATTEMPTS=3
i=1
migrate_ok=false

while [ $i -le $MIG_ATTEMPTS ]; do
  echo "🧱 Executando migrations (tentativa $i/$MIG_ATTEMPTS)..."
  if php artisan migrate --force; then
    echo "✅ Migrations executadas com sucesso."
    migrate_ok=true
    break
  else
    echo "⚠️ Migrations falharam na tentativa $i."
    sleep 3
  fi
  i=$((i+1))
done

if [ "$migrate_ok" = false ]; then
  echo "❗ Não foi possível aplicar migrations (após $MIG_ATTEMPTS tentativas). Verifique logs e credenciais do DB."
fi

# --- 4) Storage link e permissões
echo "🔗 Criando storage:link (se aplicável)..."
php artisan storage:link || echo "⚠️ storage:link falhou ou já existe."

echo "🛠 Ajustando permissões em storage e bootstrap/cache..."
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# --- 5) Limpeza de caches padrões (opcional)
echo "🧹 Limpando caches de config, route e view..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# --- 6) Start php-fpm (exec para receber sinais)
echo "🐘 Iniciando php-fpm..."
exec php-fpm
