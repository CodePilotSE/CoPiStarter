# Things to do when starting a new Copistarter theme:

- Update the font sizes in _font-sizes.scss
- Run `npm run build`
- Search and replace "copistarter" with the new theme name
- Check the allowed blocks list in `assets/js/editor.js` so that it only includes the blocks that should be allowed in the theme
- Delete or rebuild the "block demo" block

# Copistarter Theme

A theme created by CodePilot AB for its customers.

## Features

- Full site editing (FSE) support
- Responsive design
- Customizable color schemes
- Typography options with system fonts
- Block editor styles
- Custom block patterns
- Sidebar and full-width layout options

### Colors and Typography

You can easily change colors and typography settings in the `theme.json` file.

### Styles

The theme uses Sass for styles. Main stylesheets are located in the `assets/scss/` directory.

To compile Sass files:

1. Make sure you have Node.js installed
2. Run `npm install` to install dependencies
3a. Run `npm run sass-dev` to continuously compile files when they are saved
3b. Run `npm run sass-compile` to compile all files once

## Deploy & GitHub Actions

Detta tema är förberett för release-baserad deploy via GitHub Actions. Två workflows finns i `.github/workflows/`:

- `deploy.yml` – körs automatiskt när en GitHub Release publiceras och:
  - checkar ut koden för releastaggen
  - kör `npm ci`
  - kör `npm run sass-compile`
  - kör `composer install --no-dev --optimize-autoloader` om `composer.json` finns
  - deployar temat till servern med `rsync` över SSH

- `rollback.yml` – körs manuellt med `workflow_dispatch` och:
  - kör samma build- och deploysteg som `deploy.yml`
  - deployar den tagg/branch du väljer i “Run workflow” → “Use workflow from”

### Nödvändiga GitHub Secrets (per kund/repo)

Läggs i GitHub under `Settings → Secrets and variables → Actions → Secrets`:

- `SSH_HOST` – hostname eller IP till servern, t.ex. `example.com`
- `SSH_USER` – användaren som deploy ska ske som, t.ex. `deployer`
- `SSH_KEY` – privat SSH-nyckel (PEM-format) med access till `SSH_USER@SSH_HOST`
- `SSH_PORT` – (valfri) SSH-port om du inte kör på 22

#### Generera deploy-nyckel (engångssetup, macOS)

Görs en gång per kundprojekt:

1. Öppna Terminal på din macOS-dator.
2. Generera ett nytt nyckelpar för just detta kundprojekt, t.ex.:

   ```bash
   ssh-keygen -t ed25519 -C "copistarter-deploy-kund-X"
   ```

   - När du får frågan “Enter file in which to save the key”: ange t.ex.  
     `~/.ssh/copistarter-deploy-kund-x`  
     (eller tryck Enter för defaultvägen, men ett separat filnamn är oftast tydligare).
   - Du kan välja att sätta en passphrase eller lämna den tom (tom är enklast för CI, men kräver att du litar på att nyckeln bara ligger som GitHub-secret).

3. Lägg till **publika nyckeln** på servern:

   - Visa den publika nyckeln:

     ```bash
     cat ~/.ssh/copistarter-deploy-kund-x.pub
     ```

   - Kopiera hela raden och klistra in i `~/.ssh/authorized_keys` för användaren `SSH_USER` på servern.

4. Lägg in **privata nyckeln** som `SSH_KEY` i GitHub:

   - Visa den privata nyckeln:

     ```bash
     cat ~/.ssh/copistarter-deploy-kund-x
     ```

   - Kopiera hela innehållet, inklusive `-----BEGIN` / `-----END`.
   - Gå till `Settings → Secrets and variables → Actions` i kundens repo.
   - Skapa ett nytt secret:
     - Name: `SSH_KEY`
     - Value: innehållet från den privata nyckeln.

5. Sätt även:
   - `SSH_HOST` – hostname/IP till servern.
   - `SSH_USER` – användaren som ska användas för deploy.
   - (ev.) `SSH_PORT` – om du inte använder port 22.

6. Spara nyckelparet säkert, t.ex. i ett delat 1Password-valv:
   - Skapa en post med t.ex. namnet `copistarter-deploy-kund-X`.
   - Lägg in både **privata** och **publika** nyckeln som fält.
   - Efter att nyckeln finns i 1Password, på servern och i GitHub Secrets kan filerna raderas från din lokala disk om du vill.

GitHub Actions-workflowet kommer sedan, vid varje körning, att skriva `SSH_KEY`-secret till `~/.ssh/id_rsa` på den tillfälliga runnern och använda den för att ansluta till servern.

### Nödvändiga GitHub Variables (per kund/repo)

Läggs i GitHub under `Settings → Secrets and variables → Actions → Variables`:

- `THEME_PATH` – absolut sökväg till temats katalog på servern, t.ex. `/var/www/html/wp-content/themes/copistarter`
- `RSYNC_DRY_RUN` – (valfri) sätt till `true` för att köra rsync i *dry-run*-läge (ingen ändring på servern, bara logg över vad som skulle ändrats). Bra vid första körning på ett nytt kundprojekt eller när du vill verifiera att `THEME_PATH` pekar rätt innan skarp deploy.

### Checklista för nytt kundprojekt

1. Klona detta repo till kundens GitHub-organisation.
2. Sätt secrets i GitHub:
   - `SSH_HOST`
   - `SSH_USER`
   - `SSH_KEY`
   - (ev. `SSH_PORT`)
3. Sätt variabeln:
   - `THEME_PATH`
4. Skapa en tagg med CalVer:
   - Första releasen en dag: `YYYY.MM.DD` (t.ex. `2026.02.17`).
   - Ytterligare releaser samma dag: `YYYY.MM.DD.1`, `YYYY.MM.DD.2` osv. (t.ex. `2026.02.17.1`).
   - Eventuella pre-releases kan märkas med suffix, t.ex. `2026.02.17-alpha.1` och markeras som *pre-release* i GitHub. **Pre-releases kör hela buildflödet men hoppar över själva deploy-steget**, så de kan användas för att testa att kompilering fungerar utan att påverka produktion.
5. Skapa en GitHub Release för den taggen (pre-release om du bara vill testa build, vanlig release om du också vill deploya).
6. Verifiera att `deploy.yml` körs klart utan fel och – för vanliga releaser – att temat uppdateras på servern.
7. För rollback: kör `rollback.yml` från Actions-fliken och välj vilken tagg som ska rullas tillbaka till.

### Uppdatera äldre kundprojekt till nytt deploy-flöde

Om du har äldre kundprojekt som bygger på en tidigare version av Copistarter kan du återanvända samma deploy-setup på två sätt:

1. **Git-remote + cherry-pick (rekommenderat om historiken hänger ihop)**
   I kundens temarepo:
   1. Lägg till detta Copistarter-repo som extra remote:
      - `git remote add copistarter https://github.com/CodePilotSE/CoPiStarter.git`
   2. Hämta senaste ändringar:
      - `git fetch copistarter`
   3. I Copistarter-repot (eller via GitHub) identifiera den commit som lade till:
      - `.github/workflows/deploy.yml`
      - `.github/workflows/rollback.yml`
      - uppdateringarna i denna `Readme.md` kring deploy
   4. I kundens repo, cherry-picka commiten:
      - `git cherry-pick <commit-hash>`
   5. Lös eventuella konflikter, committa och pusha som vanligt.
   6. Följ sedan checklistan ovan för att sätta secrets/variabler i GitHub.

2. **Kopiera filer manuellt (om historiken skiljer sig mycket)**
   - Kopiera `.github/workflows/deploy.yml` och `.github/workflows/rollback.yml` från detta repo in i kundens repo.
   - Kopiera eller återskapa relevanta delar av deploy-sektionen i `Readme.md`.
   - Sätt upp `SSH_HOST`, `SSH_USER`, `SSH_KEY`, (ev. `SSH_PORT`) och `THEME_PATH` i kundens GitHub-repo enligt ovan.
   - Testa först med en pre-release-tag för att säkerställa att builden fungerar innan du gör en skarp release med deploy.


### Block Patterns

Custom block patterns are located in the `patterns/` directory. You can modify existing patterns or add new ones to suit your needs.

## License

Copistarter is licensed under the GPL-2.0+ license.

