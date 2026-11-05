#!/usr/bin/env bash
# pre-push guard: lab-blog asset cache-buster must be current.
# Exit 1 if layout.php's asset_version() is older than the date of any
# commit touching style.css or assets/*.js that isn't yet covered.
set -e
cd "$(dirname "$0")/.."
VER=$(grep -oE "return '[0-9]+'" src/layout.php | grep -oE "[0-9]+" | head -1)
TODAY=$(date +%Y%m%d)
if [ "$VER" -lt "$TODAY" ]; then
  # only block if assets were touched since the version was set
  ASSET_COMMITS=$(git log --oneline --since="$(date -d "@$(( $(date +%s) ))" +%Y-%m-%d)" -- style.css assets/ 2>/dev/null | wc -l)
  CHANGED=$(git status --porcelain -- style.css assets/ | wc -l)
  if [ "$CHANGED" -gt 0 ]; then
    echo "ASSET GUARD: style.css/assets changed but asset_version() ($VER) is stale (today: $TODAY)."
    echo "Fix: bump src/layout.php asset_version() then re-commit."
    exit 1
  fi
fi
exit 0