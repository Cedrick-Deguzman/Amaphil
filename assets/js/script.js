const navDevices = document.getElementById('navDevices');
const navSettings = document.getElementById('navSettings');
const devicesSection = document.getElementById('devicesSection');
const settingsSection = document.getElementById('settingsSection');

navDevices.addEventListener('click', () => {
  navDevices.classList.add('active');
  navSettings.classList.remove('active');
  devicesSection.style.display = 'block';
  settingsSection.style.display = 'none';
});

navSettings.addEventListener('click', () => {
  navSettings.classList.add('active');
  navDevices.classList.remove('active');
  settingsSection.style.display = 'block';
  devicesSection.style.display = 'none';
});

const passInput = document.getElementById("radius_secret");
const toggleBtn = document.getElementById("togglePass");
let visible = false;

toggleBtn.addEventListener("click", () => {
  visible = !visible;
  passInput.type = visible ? "text" : "password";
  toggleBtn.style.color = visible ? "#3b82f6" : "#94a3b8";
});
