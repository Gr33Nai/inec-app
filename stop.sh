#!/bin/bash

echo "Stopping INEC Application Docker containers..."
docker-compose down

echo "Containers stopped. To start again: ./setup.sh"
