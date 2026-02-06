#!/bin/bash

# Script para detener los contenedores Docker

echo "🛑 Deteniendo contenedores Docker..."

docker-compose down

echo ""
echo "✅ Contenedores detenidos"
echo ""
echo "💡 Para eliminar también los volúmenes (base de datos):"
echo "   docker-compose down -v"

