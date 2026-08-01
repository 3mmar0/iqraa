# Deployment — yatmaen.ammarelgndy.cloud

## Production URL

https://yatmaen.ammarelgndy.cloud

## Server layout

| Item | Value |
|------|--------|
| App path | `/var/www/clients/yatmaen.ammarelgndy.cloud` |
| Web server | nginx + PHP 8.4-FPM |
| Database | MySQL `yatmaen` (user `yatmaen`) |
| Cache / queue / session | Redis |
| Deploy script | `/usr/local/bin/deploy-yatmaen.sh` |
| Queue worker | Supervisor `yatmaen-queue` |

## GitHub Actions CI/CD

Workflow: `.github/workflows/deploy-yatmaen.yml`

Required repository secrets:

| Secret | Description |
|--------|-------------|
| `YATMAEN_SSH_PRIVATE_KEY` | CI deploy private key (`yatmaen_ci`) |
| `YATMAEN_SSH_HOST` | `187.127.71.130` |
| `YATMAEN_SSH_USER` | `deploy` |

Push to `main` (or `master`) triggers rsync + remote `deploy-yatmaen.sh`.

## Manual deploy

```bash
sudo /usr/local/bin/deploy-yatmaen.sh
```

## Demo logins (seeded)

Password for all: `password`

- student@example.com
- instructor@example.com
- admin@example.com
- approver@example.com

## Notes

- Domain on this VPS is **ammarelgndy.cloud** (not `ammarelgendy`).
- Never commit `.env` or DB/Redis passwords.
- Rotate the VPS root password after sharing it in chat.
- Each GitHub deploy **copies** `scripts/deploy-yatmaen.sh` to `/usr/local/bin/deploy-yatmaen.sh`, then runs it (so the server always uses the script from the commit being deployed).
- The script runs `php artisan optimize:clear` before re-caching so Blade/CSS from the new release always win.
- The workflow fails if the public homepage does not contain the expected content marker after deploy.
