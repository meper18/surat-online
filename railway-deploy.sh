#!/bin/bash

# Railway deployment script with database connection retry
echo "Starting Railway deployment..."

echo "Running force Railway setup..."
php force_railway_setup.php

echo "Deployment completed successfully!"