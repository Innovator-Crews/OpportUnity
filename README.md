# OpportUnity (Firebase + Static Web)

This project runs entirely from the `web/` folder and uses Firebase Auth + Firestore as the backend.

## Quick start
1) Open `web/.env` and paste your Firebase web config values.
2) Generate the config file:
   - `cd web`
   - `npm run build:config`
3) Open `web/index.html` with Live Server.

> Note: Firebase web config values are **public** and are safe to expose. They identify your project but do not grant admin access. Real security is enforced with Firestore rules.

## Folder layout
```
OpportUnity/
  web/
    assets/
    css/
    js/
    scripts/
    *.html
  .vscode/
```

## Firebase rules
Update rules in `web/firestore.rules`, then deploy with the Firebase CLI:
- `firebase deploy --only firestore:rules`

## Helpful scripts
From `web/`:
- `npm run build:config` - generate `web/js/firebase-config.js` from `web/.env`

## Live Server
Live Server is configured to use `web/` as the root. If it opens the wrong page, reload VS Code and open `web/index.html`.
