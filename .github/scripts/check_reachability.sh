#!/bin/bash

URL=$1
if ! curl -s --fail "$URL"; then
  echo "Request to $URL failed."
  curl -s -i "$URL"
  exit 1
fi
