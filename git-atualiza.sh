#!/bin/bash
git add .
git commit -m "${1:-Atualização automática}"
git push
