#!/bin/sh

# Ensure the script is run as root
if [ "$(id -u)" -ne 0 ]; then
    echo "Please run this script as root or use sudo"
    exit 1
fi

# Define target directory and repository
TARGET_DIR="/var/www/html/frog1"
REPO_URL="https://github.com/rrobciorr/frog1.git"

# Install Git if not installed
if ! command -v git >/dev/null 2>&1; then
    echo "Git not found. Installing..."
    apk update && apk add --no-cache git
fi

# Remove existing directory if it exists
if [ -d "$TARGET_DIR" ]; then
    echo "Removing existing directory: $TARGET_DIR"
    rm -rf "$TARGET_DIR"
fi

# Clone the repository
echo "Cloning repository from $REPO_URL..."
git clone "$REPO_URL" "$TARGET_DIR"

# Set correct permissions (optional)
chown -R www-data:www-data "$TARGET_DIR"
chmod -R 755 "$TARGET_DIR"

echo "Deployment completed successfully!"

rc-service apache2 restart