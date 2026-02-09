#!/bin/sh

INPUT="$1"
OUTPUT="$2"
INTERVAL=0.5


if [ -z "$INPUT" ] || [ -z "$OUTPUT" ]; then
  echo "Usage: $0 <input_file> <output_file>"
  exit 1
fi

if [ ! -f "$INPUT" ]; then
  echo "Input file not found: $INPUT creating empty file."
  touch "$INPUT"
fi

checksum() {
  cksum "$INPUT" 2>/dev/null || echo "ERR"
}

/usr/local/bin/tailwindcss -i "$INPUT" -o "$OUTPUT" || {
      echo "Tailwind build failed (continuing watch)"
    }

LAST_SUM="$(checksum)"

echo "Watching $INPUT → $OUTPUT (every ${INTERVAL}s)"

while true; do
  sleep "$INTERVAL" || true

  NEW_SUM="$(checksum)"

  if [ "$NEW_SUM" != "$LAST_SUM" ]; then
    echo "Change detected: $INPUT"

    /usr/local/bin/tailwindcss -i "$INPUT" -o "$OUTPUT" || {
      echo "Tailwind build failed (continuing watch)"
    }

    LAST_SUM="$NEW_SUM"
  fi
done
