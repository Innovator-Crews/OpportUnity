import {
  createUserWithEmailAndPassword,
  onAuthStateChanged,
  signInWithEmailAndPassword,
  signOut
} from "https://www.gstatic.com/firebasejs/10.12.4/firebase-auth.js";
import {
  addDoc,
  collection,
  deleteDoc,
  doc,
  getDoc,
  getDocs,
  orderBy,
  query,
  setDoc,
  updateDoc,
  where
} from "https://www.gstatic.com/firebasejs/10.12.4/firebase-firestore.js";
import { auth, db, firebaseReady, serverTimestamp } from "./firebase.js";

const page = document.body.dataset.page;
const navGreeting = document.getElementById("user-greeting");
const loginLink = document.getElementById("login-link");
const signupLink = document.getElementById("signup-link");
const logoutButton = document.getElementById("logout-btn");
const envWarning = document.getElementById("env-warning");

const profileCache = new Map();

const pages = {
  login: initLogin,
  signup: initSignup,
  employer: initEmployerDashboard,
  jobseeker: initJobseekerDashboard,
  "job-listings": initJobseekerDashboard,
  "post-job": initPostJob,
  "edit-job": initEditJob
};

if (!firebaseReady) {
  if (envWarning) {
    envWarning.classList.remove("hidden");
  }
}

if (logoutButton) {
  logoutButton.addEventListener("click", async () => {
    if (!auth) {
      return;
    }
    await signOut(auth);
    window.location.href = "login.html";
  });
}

if (page && pages[page]) {
  pages[page]();
}

function setNavState(user, profile) {
  if (user && profile) {
    if (navGreeting) {
      navGreeting.textContent = `Welcome, ${profile.firstName}!`;
      navGreeting.classList.remove("hidden");
    }
    if (logoutButton) {
      logoutButton.classList.remove("hidden");
    }
    if (loginLink) {
      loginLink.classList.add("hidden");
    }
    if (signupLink) {
      signupLink.classList.add("hidden");
    }
  } else {
    if (navGreeting) {
      navGreeting.classList.add("hidden");
    }
    if (logoutButton) {
      logoutButton.classList.add("hidden");
    }
    if (loginLink) {
      loginLink.classList.remove("hidden");
    }
    if (signupLink) {
      signupLink.classList.remove("hidden");
    }
  }
}

function getApplicationId(jobId, userId) {
  return `${jobId}_${userId}`;
}

function setNotice(element, message) {
  if (!element) {
    return;
  }
  element.textContent = message;
  if (message) {
    element.classList.remove("hidden");
  } else {
    element.classList.add("hidden");
  }
}

function requireFirebaseReady() {
  if (!firebaseReady || !auth || !db) {
    throw new Error("Firebase not configured");
  }
}

async function getProfile(uid) {
  if (profileCache.has(uid)) {
    return profileCache.get(uid);
  }
  const profileSnap = await getDoc(doc(db, "users", uid));
  const profile = profileSnap.exists() ? profileSnap.data() : null;
  if (profile) {
    profileCache.set(uid, profile);
  }
  return profile;
}

function requireAuth(requiredRole) {
  requireFirebaseReady();
  return new Promise((resolve, reject) => {
    onAuthStateChanged(auth, async (user) => {
      if (!user) {
        window.location.href = "login.html";
        return reject(new Error("Not signed in"));
      }
      const profile = await getProfile(user.uid);
      if (!profile) {
        await signOut(auth);
        window.location.href = "login.html";
        return reject(new Error("Missing profile"));
      }
      setNavState(user, profile);
      if (requiredRole && profile.role !== requiredRole) {
        window.location.href = profile.role === "employer" ? "employer.html" : "jobseeker.html";
        return reject(new Error("Role mismatch"));
      }
      return resolve({ user, profile });
    });
  });
}

async function initLogin() {
  const form = document.getElementById("login-form");
  const errorBox = document.getElementById("login-error");

  if (!form) {
    return;
  }

  if (!firebaseReady) {
    setNotice(errorBox, "Firebase is not configured yet.");
    return;
  }

  onAuthStateChanged(auth, async (user) => {
    if (user) {
      const profile = await getProfile(user.uid);
      if (profile?.role === "employer") {
        window.location.href = "employer.html";
      } else if (profile?.role === "employee") {
        window.location.href = "jobseeker.html";
      }
    }
  });

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    setNotice(errorBox, "");

    const email = document.getElementById("login-email").value.trim();
    const password = document.getElementById("login-password").value;

    try {
      await signInWithEmailAndPassword(auth, email, password);
    } catch (error) {
      setNotice(errorBox, "Login failed. Check your email and password.");
    }
  });
}

async function initSignup() {
  const form = document.getElementById("signup-form");
  const errorBox = document.getElementById("signup-error");

  if (!form) {
    return;
  }

  if (!firebaseReady) {
    setNotice(errorBox, "Firebase is not configured yet.");
    return;
  }

  onAuthStateChanged(auth, async (user) => {
    if (user) {
      const profile = await getProfile(user.uid);
      if (profile?.role === "employer") {
        window.location.href = "employer.html";
      } else if (profile?.role === "employee") {
        window.location.href = "jobseeker.html";
      }
    }
  });

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    setNotice(errorBox, "");

    const firstName = document.getElementById("signup-first-name").value.trim();
    const lastName = document.getElementById("signup-last-name").value.trim();
    const email = document.getElementById("signup-email").value.trim();
    const password = document.getElementById("signup-password").value;
    const confirmPassword = document.getElementById("signup-confirm-password").value;
    const role = document.getElementById("signup-role").value;

    if (password !== confirmPassword) {
      setNotice(errorBox, "Passwords do not match.");
      return;
    }

    try {
      const credential = await createUserWithEmailAndPassword(auth, email, password);
      await setDoc(doc(db, "users", credential.user.uid), {
        firstName,
        lastName,
        email,
        role
      });
      window.location.href = role === "employer" ? "employer.html" : "jobseeker.html";
    } catch (error) {
      setNotice(errorBox, "Signup failed. Please try again.");
    }
  });
}

async function initEmployerDashboard() {
  const jobsContainer = document.getElementById("employer-jobs");
  const emptyNotice = document.getElementById("employer-empty");

  if (!jobsContainer) {
    return;
  }

  const { user } = await requireAuth("employer");

  const jobQuery = query(
    collection(db, "job_posts"),
    where("employerId", "==", user.uid),
    orderBy("createdAt", "desc")
  );
  const jobSnapshot = await getDocs(jobQuery);
  const jobs = jobSnapshot.docs.map((docSnap) => ({
    id: docSnap.id,
    ...docSnap.data()
  }));

  if (!jobs.length && emptyNotice) {
    emptyNotice.classList.remove("hidden");
  }

  for (const job of jobs) {
    const jobCard = document.createElement("div");
    jobCard.className = "job-card";
    jobCard.innerHTML = `
      <h3>${job.jobTitle}</h3>
      <p><strong>Company:</strong> ${job.companyName}</p>
      <p><strong>Location:</strong> ${job.jobLocation}</p>
      <p><strong>Salary:</strong> ${job.salary}</p>
      <p><strong>Description:</strong> ${job.jobDescription}</p>
      <p><strong>Requirements:</strong> ${job.requirements}</p>
      <p><strong>Qualities:</strong> ${job.qualities}</p>
      <p><strong>Expectations:</strong> ${job.expectations}</p>
      <div class="actions-row">
        <a class="btn secondary" href="edit-job.html?jobId=${job.id}">Edit</a>
        <button class="btn secondary" data-delete="${job.id}">Delete</button>
      </div>
      <div>
        <h4>Applicants</h4>
        <ul id="applicants-${job.id}"></ul>
      </div>
    `;
    jobsContainer.appendChild(jobCard);

    const deleteButton = jobCard.querySelector(`[data-delete="${job.id}"]`);
    deleteButton.addEventListener("click", async () => {
      const confirmed = window.confirm("Delete this job post?");
      if (!confirmed) {
        return;
      }
      await deleteDoc(doc(db, "job_posts", job.id));
      await deleteApplicationsForJob(job.id);
      jobCard.remove();
    });

    const list = jobCard.querySelector(`#applicants-${job.id}`);
    await renderApplicants(job.id, list);
  }
}

async function renderApplicants(jobId, list) {
  const appQuery = query(collection(db, "job_applications"), where("jobId", "==", jobId));
  const appSnapshot = await getDocs(appQuery);

  if (!appSnapshot.docs.length) {
    const item = document.createElement("li");
    item.textContent = "No applicants yet.";
    list.appendChild(item);
    return;
  }

  for (const appDoc of appSnapshot.docs) {
    const appData = appDoc.data();
    const userProfile = await getProfile(appData.userId);
    const item = document.createElement("li");
    if (userProfile) {
      item.textContent = `${userProfile.firstName} ${userProfile.lastName} - ${userProfile.email}`;
    } else {
      item.textContent = appData.userId;
    }
    list.appendChild(item);
  }
}

async function deleteApplicationsForJob(jobId) {
  const appQuery = query(collection(db, "job_applications"), where("jobId", "==", jobId));
  const appSnapshot = await getDocs(appQuery);
  for (const appDoc of appSnapshot.docs) {
    await deleteDoc(appDoc.ref);
  }
}

async function initJobseekerDashboard() {
  const jobsContainer = document.getElementById("jobseeker-jobs");
  const emptyNotice = document.getElementById("jobseeker-empty");

  if (!jobsContainer) {
    return;
  }

  const { user } = await requireAuth("employee");

  const jobQuery = query(collection(db, "job_posts"), orderBy("createdAt", "desc"));
  const jobSnapshot = await getDocs(jobQuery);
  const jobs = jobSnapshot.docs.map((docSnap) => ({
    id: docSnap.id,
    ...docSnap.data()
  }));

  if (!jobs.length && emptyNotice) {
    emptyNotice.classList.remove("hidden");
  }

  for (const job of jobs) {
    const jobCard = document.createElement("div");
    jobCard.className = "job-card";
    jobCard.innerHTML = `
      <h3>${job.jobTitle}</h3>
      <p><strong>Description:</strong> ${job.jobDescription}</p>
      <p><strong>Requirements:</strong> ${job.requirements}</p>
      <p><strong>Qualifications:</strong> ${job.qualities}</p>
      <p><strong>Expectations:</strong> ${job.expectations}</p>
      <button class="btn" data-apply="${job.id}">Apply Now</button>
      <div class="notice hidden" id="apply-msg-${job.id}"></div>
    `;
    jobsContainer.appendChild(jobCard);

    const applyButton = jobCard.querySelector(`[data-apply="${job.id}"]`);
    const messageBox = jobCard.querySelector(`#apply-msg-${job.id}`);

    applyButton.addEventListener("click", async () => {
      const alreadyApplied = await hasApplied(job.id, user.uid);
      if (alreadyApplied) {
        messageBox.textContent = "You already applied to this job.";
        messageBox.classList.remove("hidden");
        return;
      }
      const applicationId = getApplicationId(job.id, user.uid);
      await setDoc(doc(db, "job_applications", applicationId), {
        jobId: job.id,
        userId: user.uid,
        createdAt: serverTimestamp()
      });
      messageBox.textContent = "Application submitted!";
      messageBox.classList.remove("hidden");
    });
  }
}

async function hasApplied(jobId, userId) {
  const applicationId = getApplicationId(jobId, userId);
  const appSnap = await getDoc(doc(db, "job_applications", applicationId));
  return appSnap.exists();
}

async function initPostJob() {
  const form = document.getElementById("post-job-form");
  const errorBox = document.getElementById("post-job-error");

  if (!form) {
    return;
  }

  const { user } = await requireAuth("employer");

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    setNotice(errorBox, "");

    const jobTitle = document.getElementById("job-title").value.trim();
    const companyName = document.getElementById("company-name").value.trim();
    const jobLocation = document.getElementById("job-location").value.trim();
    const jobDescription = document.getElementById("job-description").value.trim();
    const salary = document.getElementById("salary").value.trim();
    const requirements = document.getElementById("requirements").value.trim();
    const qualities = document.getElementById("qualities").value.trim();
    const expectations = document.getElementById("expectations").value.trim();

    if (!jobTitle || !companyName || !jobLocation || !jobDescription) {
      setNotice(errorBox, "All fields are required.");
      return;
    }

    await addDoc(collection(db, "job_posts"), {
      employerId: user.uid,
      jobTitle,
      companyName,
      jobLocation,
      jobDescription,
      salary,
      requirements,
      qualities,
      expectations,
      createdAt: serverTimestamp()
    });

    window.location.href = "employer.html";
  });
}

async function initEditJob() {
  const form = document.getElementById("edit-job-form");
  const errorBox = document.getElementById("edit-job-error");

  if (!form) {
    return;
  }

  const { user } = await requireAuth("employer");
  const params = new URLSearchParams(window.location.search);
  const jobId = params.get("jobId");

  if (!jobId) {
    setNotice(errorBox, "Missing jobId.");
    return;
  }

  const jobRef = doc(db, "job_posts", jobId);
  const jobSnap = await getDoc(jobRef);

  if (!jobSnap.exists()) {
    setNotice(errorBox, "Job not found.");
    return;
  }

  const job = jobSnap.data();
  if (job.employerId !== user.uid) {
    setNotice(errorBox, "You do not have access to this job.");
    return;
  }

  document.getElementById("job-title").value = job.jobTitle ?? "";
  document.getElementById("company-name").value = job.companyName ?? "";
  document.getElementById("job-location").value = job.jobLocation ?? "";
  document.getElementById("job-description").value = job.jobDescription ?? "";
  document.getElementById("salary").value = job.salary ?? "";
  document.getElementById("requirements").value = job.requirements ?? "";
  document.getElementById("qualities").value = job.qualities ?? "";
  document.getElementById("expectations").value = job.expectations ?? "";

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    setNotice(errorBox, "");

    await updateDoc(jobRef, {
      jobTitle: document.getElementById("job-title").value.trim(),
      companyName: document.getElementById("company-name").value.trim(),
      jobLocation: document.getElementById("job-location").value.trim(),
      jobDescription: document.getElementById("job-description").value.trim(),
      salary: document.getElementById("salary").value.trim(),
      requirements: document.getElementById("requirements").value.trim(),
      qualities: document.getElementById("qualities").value.trim(),
      expectations: document.getElementById("expectations").value.trim()
    });

    window.location.href = "employer.html";
  });
}
