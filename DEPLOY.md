# Deployment — iqraa.ammarelgndy.cloud

## Production URL

https://iqraa.ammarelgndy.cloud

## Server layout

| Item | Value |
|------|--------|
| App path | `/var/www/clients/iqraa.ammarelgndy.cloud` |
| Web server | nginx + PHP 8.4-FPM |
| Database | MySQL `iqraa` (user `iqraa`) |
| Cache / queue / session | Redis |
| Deploy script | `/usr/local/bin/deploy-iqraa.sh` |
| Queue worker | Supervisor `iqraa-queue` |

## GitHub Actions CI/CD

Workflow: `.github/workflows/deploy-iqraa.yml`

Required repository secrets:

| Secret | Description |
|--------|-------------|
| `IQRAA_SSH_PRIVATE_KEY` | CI deploy private key |
| `IQRAA_SSH_HOST` | `187.127.71.130` |
| `IQRAA_SSH_USER` | `deploy` |

Push to `main` (or `master`) triggers rsync + remote `deploy-iqraa.sh`.

## First-time server setup

Run on the VPS as a user with sudo:

```bash
sudo bash scripts/server-setup-iqraa.sh
```

This creates the app directory, MySQL database/user, nginx vhost, SSL cert, supervisor worker, and `.env` from `.env.example`.

## Manual deploy

```bash
sudo /usr/local/bin/deploy-iqraa.sh
```

## Demo logins (seeded)

Password for all: `password`

- student@example.com
- instructor@example.com
- admin@example.com
- approver@example.com

## Notes

- Domain on this VPS is **ammarelgndy.cloud**.
- Never commit `.env` or DB/Redis passwords.
- Each GitHub deploy **copies** `scripts/deploy-iqraa.sh` to `/usr/local/bin/deploy-iqraa.sh`, then runs it.
