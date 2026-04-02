#!/usr/bin/env bash
set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

ok()   { echo -e "${GREEN}✔${NC} $1"; }
warn() { echo -e "${YELLOW}⚠${NC}  $1"; }
die()  { echo -e "${RED}✘${NC} $1"; exit 1; }
info() { echo -e "${BLUE}→${NC} $1"; }

echo ""
echo "=== API Middleware Setup ==="
echo ""

OS=$(uname -s)

# ── Install Docker (macOS) ───────────────────────────────────────────

install_docker_mac() {
    if command -v brew &>/dev/null; then
        info "Installing Docker Desktop via Homebrew..."
        brew install --cask docker
    else
        die "Homebrew is not installed. Either install Homebrew (https://brew.sh) or install Docker Desktop manually (https://www.docker.com/products/docker-desktop/)"
    fi
}

# ── Install Docker (Linux) ───────────────────────────────────────────

install_docker_linux() {
    if [ ! -f /etc/os-release ]; then
        die "Cannot detect Linux distro. Install Docker manually: https://docs.docker.com/get-docker/"
    fi

    . /etc/os-release

    case "$ID" in
        ubuntu|debian|linuxmint|pop)
            info "Installing Docker on ${PRETTY_NAME}..."
            sudo apt-get update -qq
            sudo apt-get install -y -qq ca-certificates curl gnupg
            sudo install -m 0755 -d /etc/apt/keyrings
            curl -fsSL https://download.docker.com/linux/${ID}/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
            echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] \
                https://download.docker.com/linux/${ID} $(. /etc/os-release && echo "$VERSION_CODENAME") stable" \
                | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
            sudo apt-get update -qq
            sudo apt-get install -y -qq docker-ce docker-ce-cli containerd.io docker-compose-plugin
            sudo systemctl enable --now docker
            sudo usermod -aG docker "$USER"
            warn "You have been added to the docker group. Log out and back in for this to take effect, then re-run this script."
            exit 0
            ;;
        fedora|rhel|centos|rocky|almalinux)
            info "Installing Docker on ${PRETTY_NAME}..."
            sudo dnf -y install dnf-plugins-core
            sudo dnf config-manager --add-repo https://download.docker.com/linux/fedora/docker-ce.repo
            sudo dnf -y install docker-ce docker-ce-cli containerd.io docker-compose-plugin
            sudo systemctl enable --now docker
            sudo usermod -aG docker "$USER"
            warn "You have been added to the docker group. Log out and back in for this to take effect, then re-run this script."
            exit 0
            ;;
        *)
            die "Unsupported distro: ${ID}. Install Docker manually: https://docs.docker.com/get-docker/"
            ;;
    esac
}

# ── Install Docker Compose plugin (Linux only) ───────────────────────

install_compose_linux() {
    if [ ! -f /etc/os-release ]; then
        die "Cannot detect Linux distro. Install Docker Compose manually: https://docs.docker.com/compose/"
    fi

    . /etc/os-release

    case "$ID" in
        ubuntu|debian|linuxmint|pop)
            info "Installing Docker Compose plugin..."
            sudo apt-get update -qq
            sudo apt-get install -y -qq docker-compose-plugin
            ;;
        fedora|rhel|centos|rocky|almalinux)
            info "Installing Docker Compose plugin..."
            sudo dnf -y install docker-compose-plugin
            ;;
        *)
            die "Unsupported distro: ${ID}. Install Docker Compose manually: https://docs.docker.com/compose/"
            ;;
    esac
}

# ── Wait for Docker daemon (macOS) ───────────────────────────────────

wait_for_docker_mac() {
    info "Starting Docker Desktop..."
    open -a Docker
    echo -n "   Waiting for Docker daemon"
    for i in $(seq 1 30); do
        docker info &>/dev/null 2>&1 && echo "" && return 0
        echo -n "."
        sleep 2
    done
    echo ""
    die "Docker daemon did not start after 60s. Open Docker Desktop manually and try again."
}

# ── Dependency checks & auto-install ────────────────────────────────

# Docker binary
if ! command -v docker &>/dev/null; then
    warn "Docker not found. Installing..."
    [ "$OS" = "Darwin" ] && install_docker_mac || install_docker_linux
fi
ok "Docker found"

# Docker daemon
if ! docker info &>/dev/null 2>&1; then
    if [ "$OS" = "Darwin" ]; then
        wait_for_docker_mac
    else
        info "Starting Docker daemon..."
        sudo systemctl start docker
        sleep 3
        docker info &>/dev/null 2>&1 || die "Docker daemon failed to start. Try: sudo systemctl start docker"
    fi
fi
ok "Docker daemon running"

# Docker Compose v2
if ! docker compose version &>/dev/null 2>&1; then
    warn "Docker Compose v2 not found. Installing..."
    if [ "$OS" = "Darwin" ]; then
        command -v brew &>/dev/null || die "Homebrew not found. Install Docker Desktop (includes Compose): https://www.docker.com/products/docker-desktop/"
        brew install docker-compose
        mkdir -p ~/.docker/cli-plugins
        ln -sfn "$(brew --prefix)/opt/docker-compose/bin/docker-compose" ~/.docker/cli-plugins/docker-compose
    else
        install_compose_linux
    fi
    docker compose version &>/dev/null 2>&1 || die "Docker Compose installation failed. Install manually: https://docs.docker.com/compose/"
fi

DOCKER_VERSION=$(docker --version | awk '{print $3}' | tr -d ',')
COMPOSE_VERSION=$(docker compose version 2>/dev/null | awk '{print $NF}')
ok "Docker ${DOCKER_VERSION}"
ok "Docker Compose ${COMPOSE_VERSION}"

# ── Generate app keys ────────────────────────────────────────────────

generate_key() {
    if command -v openssl &>/dev/null; then
        echo "base64:$(openssl rand -base64 32)"
    elif command -v php &>/dev/null; then
        php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
    elif command -v python3 &>/dev/null; then
        python3 -c "import base64, os; print('base64:' + base64.b64encode(os.urandom(32)).decode())"
    else
        die "Cannot generate app keys. Install openssl, PHP, or Python 3 and try again."
    fi
}

FIONA_KEY=$(generate_key)
PLUCKER_KEY=$(generate_key)
ok "App keys generated"

# ── Generate DB passwords ─────────────────────────────────────────────

generate_password() {
    if command -v openssl &>/dev/null; then
        openssl rand -hex 16
    else
        LC_ALL=C tr -dc 'a-zA-Z0-9' < /dev/urandom | head -c 32
    fi
}

DB_ROOT_PASS=$(generate_password)
DB_PASS=$(generate_password)

# ── Create .env ───────────────────────────────────────────────

if [ -f .env ] && grep -q "DB_ROOT_PASSWORD" .env; then
    warn ".env already exists with DB config — skipping (delete it to regenerate)"
else
    cat > .env <<EOF
FIONA_APP_KEY=${FIONA_KEY}
PLUCKER_APP_KEY=${PLUCKER_KEY}

DB_ROOT_PASSWORD=${DB_ROOT_PASS}
DB_DATABASE=apimiddleware
DB_USERNAME=apimiddleware
DB_PASSWORD=${DB_PASS}

APP_ENV=local
APP_DEBUG=true
EOF
    ok ".env created"
fi

# ── Build and start ──────────────────────────────────────────────────

echo ""
info "Building and starting containers (this may take a few minutes on first run)..."
echo ""

docker compose up --build -d

echo ""
ok "All containers started"
echo ""
echo "  Fiona (API gateway)   →  http://localhost:8000"
echo "  Plucker (Admin panel) →  http://localhost:8001"
echo ""
echo "To view logs:  docker compose logs -f"
echo "To stop:       docker compose down"
echo ""
