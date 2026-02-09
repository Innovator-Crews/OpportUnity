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

function setLoadingState(element, isLoading, label) {
  if (!element) {
    return;
  }
  element.classList.toggle("hidden", !isLoading);
  const textEl = element.querySelector("[data-loading-text]");
  if (textEl && label) {
    textEl.textContent = label;
  }
}

function setButtonLoading(button, isLoading, label) {
  if (!button) {
    return;
  }
  if (isLoading) {
    if (!button.dataset.originalText) {
      button.dataset.originalText = button.textContent;
    }
    button.textContent = label || "Loading...";
    button.disabled = true;
  } else {
    button.textContent = button.dataset.originalText || button.textContent;
    button.disabled = false;
  }
}

function normalizeText(value) {
  return String(value || "").trim().toLowerCase();
}

function escapeHtml(value) {
  return String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/\"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

function isValidEmail(value) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
}

function getAuthErrorMessage(error) {
  const code = error?.code || "";
  const messages = {
    "auth/invalid-email": "Please enter a valid email address.",
    "auth/user-disabled": "This account has been disabled.",
    "auth/user-not-found": "No account found for this email.",
    "auth/wrong-password": "Incorrect password. Please try again.",
    "auth/email-already-in-use": "An account already exists for this email.",
    "auth/weak-password": "Password must be at least 6 characters.",
    "auth/too-many-requests": "Too many attempts. Please wait and try again."
  };
  return messages[code] || "Something went wrong. Please try again.";
}

function validateJobForm(values) {
  if (!values.jobTitle || values.jobTitle.length < 2) {
    return "Job title must be at least 2 characters.";
  }
  if (!values.companyName || values.companyName.length < 2) {
    return "Company name must be at least 2 characters.";
  }
  if (!values.jobLocation || values.jobLocation.length < 2) {
    return "Location must be at least 2 characters.";
  }
  if (!values.jobDescription || values.jobDescription.length < 20) {
    return "Job description must be at least 20 characters.";
  }
  if (!values.salary) {
    return "Salary is required.";
  }
  if (!values.requirements || values.requirements.length < 3) {
    return "Requirements must be at least 3 characters.";
  }
  if (!values.qualities || values.qualities.length < 3) {
    return "Qualities must be at least 3 characters.";
  }
  if (!values.expectations || values.expectations.length < 3) {
    return "Expectations must be at least 3 characters.";
  }
  return "";
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
  const submitButton = form ? form.querySelector("button[type='submit']") : null;

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

    if (!email || !isValidEmail(email)) {
      setNotice(errorBox, "Please enter a valid email address.");
      return;
    }

    if (!password || password.length < 6) {
      setNotice(errorBox, "Password must be at least 6 characters.");
      return;
    }

    try {
      setButtonLoading(submitButton, true, "Signing in...");
      await signInWithEmailAndPassword(auth, email, password);
    } catch (error) {
      setNotice(errorBox, getAuthErrorMessage(error));
    } finally {
      setButtonLoading(submitButton, false);
    }
  });
}

async function initSignup() {
  const form = document.getElementById("signup-form");
  const errorBox = document.getElementById("signup-error");
  const submitButton = form ? form.querySelector("button[type='submit']") : null;

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

    if (!firstName || firstName.length < 2) {
      setNotice(errorBox, "First name must be at least 2 characters.");
      return;
    }

    if (!lastName || lastName.length < 2) {
      setNotice(errorBox, "Last name must be at least 2 characters.");
      return;
    }

    if (!email || !isValidEmail(email)) {
      setNotice(errorBox, "Please enter a valid email address.");
      return;
    }

    if (!password || password.length < 6) {
      setNotice(errorBox, "Password must be at least 6 characters.");
      return;
    }

    if (password !== confirmPassword) {
      setNotice(errorBox, "Passwords do not match.");
      return;
    }

    if (!role) {
      setNotice(errorBox, "Please select a role.");
      return;
    }

    try {
      setButtonLoading(submitButton, true, "Creating account...");
      const credential = await createUserWithEmailAndPassword(auth, email, password);
      await setDoc(doc(db, "users", credential.user.uid), {
        firstName,
        lastName,
        email,
        role
      });
      window.location.href = role === "employer" ? "employer.html" : "jobseeker.html";
    } catch (error) {
      setNotice(errorBox, getAuthErrorMessage(error));
    } finally {
      setButtonLoading(submitButton, false);
    }
  });
}

async function initEmployerDashboard() {
  const jobsContainer = document.getElementById("employer-jobs");
  const emptyNotice = document.getElementById("employer-empty");
  const loadingPanel = document.getElementById("employer-loading");

  if (!jobsContainer) {
    return;
  }

  let user;
  try {
    setLoadingState(loadingPanel, true, "Loading your roles...");
    ({ user } = await requireAuth("employer"));
  } catch (error) {
    setNotice(emptyNotice, "Unable to load your jobs right now.");
    return;
  }

  const jobQuery = query(
    collection(db, "job_posts"),
    where("employerId", "==", user.uid),
    orderBy("createdAt", "desc")
  );
  let jobSnapshot;
  try {
    jobSnapshot = await getDocs(jobQuery);
  } catch (error) {
    setNotice(emptyNotice, "Unable to load your jobs right now.");
    return;
  } finally {
    setLoadingState(loadingPanel, false);
  }
  const jobs = jobSnapshot.docs.map((docSnap) => ({
    id: docSnap.id,
    ...docSnap.data()
  }));

  if (!jobs.length && emptyNotice) {
    emptyNotice.classList.remove("hidden");
  }

  jobsContainer.innerHTML = "";

  for (const job of jobs) {
    const safeJobTitle = escapeHtml(job.jobTitle);
    const safeCompany = escapeHtml(job.companyName);
    const safeLocation = escapeHtml(job.jobLocation);
    const safeSalary = escapeHtml(job.salary);
    const safeDescription = escapeHtml(job.jobDescription);
    const safeRequirements = escapeHtml(job.requirements);
    const safeQualities = escapeHtml(job.qualities);
    const safeExpectations = escapeHtml(job.expectations);

    const jobCard = document.createElement("div");
    jobCard.className = "job-card";
    jobCard.innerHTML = `
      <h3>${safeJobTitle}</h3>
      <p><strong>Company:</strong> ${safeCompany}</p>
      <p><strong>Location:</strong> ${safeLocation}</p>
      <p><strong>Salary:</strong> ${safeSalary}</p>
      <p><strong>Description:</strong> ${safeDescription}</p>
      <p><strong>Requirements:</strong> ${safeRequirements}</p>
      <p><strong>Qualities:</strong> ${safeQualities}</p>
      <p><strong>Expectations:</strong> ${safeExpectations}</p>
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
      try {
        setButtonLoading(deleteButton, true, "Deleting...");
        await deleteDoc(doc(db, "job_posts", job.id));
        await deleteApplicationsForJob(job.id);
        jobCard.remove();
      } catch (error) {
        setNotice(emptyNotice, "Unable to delete that job right now.");
      } finally {
        setButtonLoading(deleteButton, false);
      }
    });

    const list = jobCard.querySelector(`#applicants-${job.id}`);
    await renderApplicants(job.id, list);
  }
}

async function renderApplicants(jobId, list) {
  const appQuery = query(collection(db, "job_applications"), where("jobId", "==", jobId));
  let appSnapshot;

  try {
    appSnapshot = await getDocs(appQuery);
  } catch (error) {
    const item = document.createElement("li");
    item.textContent = "Unable to load applicants.";
    list.appendChild(item);
    return;
  }

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
      const firstName = escapeHtml(userProfile.firstName);
      const lastName = escapeHtml(userProfile.lastName);
      const email = escapeHtml(userProfile.email);
      item.textContent = `${firstName} ${lastName} - ${email}`;
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
  const loadingPanel = document.getElementById("jobs-loading");
  const searchInput = document.getElementById("job-search");
  const locationInput = document.getElementById("job-location-filter");
  const clearFiltersButton = document.getElementById("job-filter-clear");
  const resultsCount = document.getElementById("job-results-count");

  if (!jobsContainer) {
    return;
  }

  let user;
  try {
    setLoadingState(loadingPanel, true, "Loading roles...");
    ({ user } = await requireAuth("employee"));
  } catch (error) {
    setNotice(emptyNotice, "Unable to load roles right now.");
    return;
  }

  const jobQuery = query(collection(db, "job_posts"), orderBy("createdAt", "desc"));
  let jobSnapshot;
  try {
    jobSnapshot = await getDocs(jobQuery);
  } catch (error) {
    setNotice(emptyNotice, "Unable to load roles right now.");
    return;
  } finally {
    setLoadingState(loadingPanel, false);
  }
  const jobs = jobSnapshot.docs.map((docSnap) => ({
    id: docSnap.id,
    ...docSnap.data()
  }));

  const renderJobs = async (jobsToRender) => {
    jobsContainer.innerHTML = "";
    if (!jobsToRender.length && emptyNotice) {
      emptyNotice.classList.remove("hidden");
    } else if (emptyNotice) {
      emptyNotice.classList.add("hidden");
    }

    if (resultsCount) {
      resultsCount.textContent = `${jobsToRender.length} role${jobsToRender.length === 1 ? "" : "s"}`;
    }

    for (const job of jobsToRender) {
      const jobCard = document.createElement("div");
      jobCard.className = "job-card";
      jobCard.innerHTML = `
        <h3>${escapeHtml(job.jobTitle)}</h3>
        <p><strong>Description:</strong> ${escapeHtml(job.jobDescription)}</p>
        <p><strong>Requirements:</strong> ${escapeHtml(job.requirements)}</p>
        <p><strong>Qualifications:</strong> ${escapeHtml(job.qualities)}</p>
        <p><strong>Expectations:</strong> ${escapeHtml(job.expectations)}</p>
        <button class="btn" data-apply="${job.id}">Apply Now</button>
        <div class="notice hidden" id="apply-msg-${job.id}"></div>
      `;
      jobsContainer.appendChild(jobCard);

      const applyButton = jobCard.querySelector(`[data-apply="${job.id}"]`);
      const messageBox = jobCard.querySelector(`#apply-msg-${job.id}`);

      applyButton.addEventListener("click", async () => {
        try {
          setButtonLoading(applyButton, true, "Applying...");
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
        } catch (error) {
          messageBox.textContent = "Unable to submit application right now.";
          messageBox.classList.remove("hidden");
        } finally {
          setButtonLoading(applyButton, false);
        }
      });
    }
  };

  const applyFilters = () => {
    const searchValue = normalizeText(searchInput?.value);
    const locationValue = normalizeText(locationInput?.value);

    const filtered = jobs.filter((job) => {
      const haystack = normalizeText(
        `${job.jobTitle} ${job.companyName} ${job.jobDescription} ${job.requirements} ${job.qualities} ${job.expectations}`
      );
      const location = normalizeText(job.jobLocation);

      const matchesSearch = !searchValue || haystack.includes(searchValue);
      const matchesLocation = !locationValue || location.includes(locationValue);

      return matchesSearch && matchesLocation;
    });

    renderJobs(filtered);
  };

  if (searchInput || locationInput || clearFiltersButton) {
    searchInput?.addEventListener("input", applyFilters);
    locationInput?.addEventListener("input", applyFilters);
    clearFiltersButton?.addEventListener("click", () => {
      if (searchInput) {
        searchInput.value = "";
      }
      if (locationInput) {
        locationInput.value = "";
      }
      applyFilters();
    });
  }

  await renderJobs(jobs);
}

async function hasApplied(jobId, userId) {
  const applicationId = getApplicationId(jobId, userId);
  const appSnap = await getDoc(doc(db, "job_applications", applicationId));
  return appSnap.exists();
}

async function initPostJob() {
  const form = document.getElementById("post-job-form");
  const errorBox = document.getElementById("post-job-error");
  const submitButton = form ? form.querySelector("button[type='submit']") : null;

  if (!form) {
    return;
  }

  let user;
  try {
    ({ user } = await requireAuth("employer"));
  } catch (error) {
    setNotice(errorBox, "You need an employer account to post jobs.");
    return;
  }

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

    const validationMessage = validateJobForm({
      jobTitle,
      companyName,
      jobLocation,
      jobDescription,
      salary,
      requirements,
      qualities,
      expectations
    });

    if (validationMessage) {
      setNotice(errorBox, validationMessage);
      return;
    }

    try {
      setButtonLoading(submitButton, true, "Posting...");
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
    } catch (error) {
      setNotice(errorBox, "Unable to post this job right now.");
    } finally {
      setButtonLoading(submitButton, false);
    }
  });
}

async function initEditJob() {
  const form = document.getElementById("edit-job-form");
  const errorBox = document.getElementById("edit-job-error");
  const submitButton = form ? form.querySelector("button[type='submit']") : null;

  if (!form) {
    return;
  }

  let user;
  try {
    ({ user } = await requireAuth("employer"));
  } catch (error) {
    setNotice(errorBox, "You need an employer account to edit jobs.");
    return;
  }
  const params = new URLSearchParams(window.location.search);
  const jobId = params.get("jobId");

  if (!jobId) {
    setNotice(errorBox, "Missing jobId.");
    return;
  }

  const jobRef = doc(db, "job_posts", jobId);
  let jobSnap;
  try {
    jobSnap = await getDoc(jobRef);
  } catch (error) {
    setNotice(errorBox, "Unable to load this job right now.");
    return;
  }

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

    const jobTitle = document.getElementById("job-title").value.trim();
    const companyName = document.getElementById("company-name").value.trim();
    const jobLocation = document.getElementById("job-location").value.trim();
    const jobDescription = document.getElementById("job-description").value.trim();
    const salary = document.getElementById("salary").value.trim();
    const requirements = document.getElementById("requirements").value.trim();
    const qualities = document.getElementById("qualities").value.trim();
    const expectations = document.getElementById("expectations").value.trim();

    const validationMessage = validateJobForm({
      jobTitle,
      companyName,
      jobLocation,
      jobDescription,
      salary,
      requirements,
      qualities,
      expectations
    });

    if (validationMessage) {
      setNotice(errorBox, validationMessage);
      return;
    }

    try {
      setButtonLoading(submitButton, true, "Updating...");
      await updateDoc(jobRef, {
        jobTitle,
        companyName,
        jobLocation,
        jobDescription,
        salary,
        requirements,
        qualities,
        expectations
      });

      window.location.href = "employer.html";
    } catch (error) {
      setNotice(errorBox, "Unable to update this job right now.");
    } finally {
      setButtonLoading(submitButton, false);
    }
  });
}
