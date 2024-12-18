#!/bin/bash

URL=$1

# Capture only the HTTP status code
STATUS=$(curl -s -o /dev/null -w "%{http_code}" "$URL")

# Check if the status code is not 2xx
if [[ "$STATUS" -lt 200 || "$STATUS" -ge 400 ]]; then
  echo "Request to $URL failed with status code: $STATUS"
  echo "Response details:"
  curl -s -i "$URL"
  exit 1
else
  echo "Request to $URL succeeded with status code: $STATUS"
fi
