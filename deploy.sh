#!/usr/bin/env bash
#
# deploy.sh — urcă site-ul pe server prin rsync peste SSH.
#
# Pentru cine are acces SSH la găzduire. E metoda curată: sincronizează doar ce
# trebuie, exclude configurarea cu parole și imaginile încărcate, și pune codul
# deasupra rădăcinii web, cum trebuie.
#
# Folosire:
#   1. Completează variabilele de mai jos (SSH_USER, SSH_HOST, REMOTE_HOME).
#   2. Rulează:  bash deploy.sh
#
# Rulează un „dry run" întâi (arată ce ar copia, fără să copieze):
#   bash deploy.sh --dry-run

set -euo pipefail

# --- Configurare — COMPLETEAZĂ ---------------------------------------------
SSH_USER="jgtdsdzd"                 # utilizatorul cPanel/SSH
SSH_HOST="catalog360.ro"            # sau IP-ul serverului
SSH_PORT="22"                       # portul SSH (unele găzduiri folosesc altul)
REMOTE_HOME="/home/${SSH_USER}"     # directorul home de pe server
# ---------------------------------------------------------------------------

LOCAL_DIR="$(cd "$(dirname "$0")" && pwd)"
DRY=""
[ "${1:-}" = "--dry-run" ] && DRY="--dry-run" && echo ">>> DRY RUN — nu se copiază nimic, doar se arată."

RSYNC="rsync -avz --delete $DRY -e \"ssh -p ${SSH_PORT}\""

echo ">>> 1/2  Urc conținutul public_html/ în rădăcina web (${REMOTE_HOME}/public_html)"
eval $RSYNC \
  --exclude 'uploads/' \
  "\"${LOCAL_DIR}/public_html/\"" \
  "\"${SSH_USER}@${SSH_HOST}:${REMOTE_HOME}/public_html/\""

echo ">>> 2/2  Urc codul (src, config-example, content-export) DEASUPRA rădăcinii web"
# config.php (cu parole) NU se sincronizează — se creează o dată pe server.
# schema.sql și seed.php se urcă (utile la instalare), pot fi șterse apoi.
eval $RSYNC \
  --exclude 'config.php' \
  "\"${LOCAL_DIR}/src\"" \
  "\"${LOCAL_DIR}/config\"" \
  "\"${LOCAL_DIR}/content-export\"" \
  "\"${LOCAL_DIR}/schema.sql\"" \
  "\"${LOCAL_DIR}/seed.php\"" \
  "\"${SSH_USER}@${SSH_HOST}:${REMOTE_HOME}/\""

echo ""
echo ">>> Gata."
echo "    Ce NU s-a urcat (intenționat): config/config.php, uploads/, node_modules/,"
echo "    fișierele de dezvoltare (assets-src, package.json, *.md de dev, router-dev.php)."
echo ""
echo "    Prima dată: creează config/config.php pe server din config.example.php,"
echo "    importă schema.sql și rulează 'php seed.php'. Vezi INSTALARE.md."
