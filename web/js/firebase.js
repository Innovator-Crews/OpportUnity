import { initializeApp, getApps } from "https://www.gstatic.com/firebasejs/10.12.4/firebase-app.js";
import { getAuth } from "https://www.gstatic.com/firebasejs/10.12.4/firebase-auth.js";
import { getFirestore, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.12.4/firebase-firestore.js";
import { firebaseConfig } from "./firebase-config.js";

export const firebaseReady = Object.values(firebaseConfig).every((value) => Boolean(value));

const app = firebaseReady
  ? getApps().length
    ? getApps()[0]
    : initializeApp(firebaseConfig)
  : null;

export const auth = app ? getAuth(app) : null;
export const db = app ? getFirestore(app) : null;
export { serverTimestamp };
