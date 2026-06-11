
const panels = document.querySelectorAll('.panel');
const navItems = document.querySelectorAll('.nav-item');
const titles = { dashboard:'Dashboard', marcas:'Marcas', perfumes:'Perfumes', colecciones:'Colecciones', acordes:'Acordes Olfativos', notas:'Notas Olfativas', galerias:'Galerías', decants:'Decants' };

function showPanel(name) {
  panels.forEach(p => p.classList.remove('active'));
  navItems.forEach(n => n.classList.remove('active'));
  document.getElementById('panel-' + name).classList.add('active');
  document.getElementById('topbar-title').textContent = titles[name] || name;
  const idx = Object.keys(titles).indexOf(name);
  navItems[idx]?.classList.add('active');
}

function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const main = document.getElementById('main');
  sidebar.classList.toggle('collapsed');
  main.classList.toggle('collapsed');
}

function toggleSub(subId, itemEl) {
  const sub = document.getElementById(subId);
  if (!sub) return;
  const isOpen = sub.classList.contains('open');
  document.querySelectorAll('.nav-sub.open').forEach(s => s.classList.remove('open'));
  document.querySelectorAll('.chevron.open').forEach(c => c.classList.remove('open'));
  if (!isOpen) {
    sub.classList.add('open');
    const chevId = 'chev-' + subId.replace('sub-','');
    const chev = document.getElementById(chevId);
    if (chev) chev.classList.add('open');
  }
}

function autoSlug(input) {
  const slug = input.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-');
  document.getElementById('slug-field').value = slug;
}

function updateCounter(el, id) { document.getElementById(id).textContent = el.value.length; }

function updateRange(input, id) {
  document.getElementById(id).textContent = input.value;
  const pct = ((input.value - input.min) / (input.max - input.min)) * 100;
  input.style.setProperty('--val', pct + '%');
}

function previewSingle(input, imgId) {
  const file = input.files[0];
  if (!file) return;
  const img = document.getElementById(imgId);
  img.src = URL.createObjectURL(file);
  img.style.display = 'block';
}

function previewMultiple(input, containerId) {
  const container = document.getElementById(containerId);
  container.innerHTML = '';
  Array.from(input.files).forEach(file => {
    const div = document.createElement('div');
    div.className = 'preview-thumb';
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    div.appendChild(img); container.appendChild(div);
  });
}

function handleTag(e, areaId, hiddenId) {
  if (e.key !== 'Enter' && e.key !== ',') return;
  e.preventDefault();
  const input = e.target;
  const val = input.value.trim();
  if (!val) return;
  addTag(val, areaId, hiddenId);
  input.value = '';
}

function addTag(val, areaId, hiddenId) {
  const area = document.getElementById(areaId);
  const tag = document.createElement('span');
  tag.className = 'tag';
  tag.innerHTML = `${val}<span class="tag-remove" onclick="removeTag(this,'${hiddenId}')">×</span>`;
  tag.dataset.value = val;
  const inp = area.querySelector('.tag-input');
  area.insertBefore(tag, inp);
  updateHidden(areaId, hiddenId);
}

function removeTag(el, hiddenId) {
  const tag = el.parentElement;
  const area = tag.parentElement;
  tag.remove();
  updateHidden(area.id, hiddenId);
}

function updateHidden(areaId, hiddenId) {
  const area = document.getElementById(areaId);
  const vals = Array.from(area.querySelectorAll('.tag')).map(t => t.dataset.value);
  document.getElementById(hiddenId).value = vals.join(',');
}

function syncColor(input) {
  const colorPicker = input.previousElementSibling;
  if (input.value.match(/^#[0-9A-Fa-f]{6}$/)) colorPicker.value = input.value;
}

const decants = [];
function addDecant() {
  const v = document.getElementById('d-volumen').value;
  const p = document.getElementById('d-precio').value;
  const s = document.getElementById('d-stock').value;
  if (!v || !p) { showToast('Ingresa volumen y precio', '⚠'); return; }
  decants.push({ volumen_ml: parseInt(v), precio: parseFloat(p), stock: parseInt(s||0) });
  renderDecants();
  document.getElementById('d-volumen').value = '';
  document.getElementById('d-precio').value = '';
  document.getElementById('d-stock').value = '';
}

function renderDecants() {
  const list = document.getElementById('decants-list');
  list.innerHTML = '';
  decants.forEach((d,i) => {
    const row = document.createElement('div');
    row.className = 'decant-row';
    row.innerHTML = `<span class="decant-badge">${d.volumen_ml} ml</span><span>${new Intl.NumberFormat('es-CO',{style:'currency',currency:'COP',maximumFractionDigits:0}).format(d.precio)}</span><span style="color:var(--ink-soft)">Stock: <strong>${d.stock}</strong></span><button type="button" class="btn btn-danger" onclick="removeDecant(${i})" style="padding:6px 12px;font-size:12px;">Eliminar</button>`;
    list.appendChild(row);
  });
  document.getElementById('decants-json').value = JSON.stringify(decants);
}

function removeDecant(i) { decants.splice(i,1); renderDecants(); }

function handleSave() {
  const activePanel = document.querySelector('.panel.active');
  const form = activePanel.querySelector('form');
  if (!form) return;
  if (!form.checkValidity()) { form.reportValidity(); return; }
  form.submit();
}

function resetForm() {
  const activePanel = document.querySelector('.panel.active');
  const form = activePanel.querySelector('form');
  if (form) {
    form.reset();
    activePanel.querySelectorAll('img[id]').forEach(img => img.style.display = 'none');
    activePanel.querySelectorAll('.preview-grid').forEach(g => g.innerHTML = '');
    activePanel.querySelectorAll('.tag-area').forEach(area => area.querySelectorAll('.tag').forEach(t => t.remove()));
    activePanel.querySelectorAll('[id^="counter-"]').forEach(c => c.textContent = '0');
  }
  showToast('Formulario limpiado', '◎');
}

function showToast(msg, icon='✦') {
  const container = document.getElementById('toasts');
  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML = `<span style="font-size:16px;color:var(--gold);">${icon}</span> ${msg}`;
  container.appendChild(toast);
  setTimeout(() => { toast.style.opacity='0'; toast.style.transform='translateX(30px)'; toast.style.transition='0.3s'; setTimeout(()=>toast.remove(),350); }, 3500);
}

document.querySelectorAll('input[type="range"]').forEach(r => {
  const pct = ((r.value - r.min) / (r.max - r.min)) * 100;
  r.style.setProperty('--val', pct + '%');
});

window.addEventListener('DOMContentLoaded', () => {
    alert('✅ JS exitoso');
});

function cargarStats() {
  fetch('get_stats.php')
    .then(res => res.json())
    .then(json => {
      if (!json.success) return;
      const d = json.data;

      animarContador('stat-perfumes', d.perfumes);
      animarContador('stat-marcas',   d.marcas);
      animarContador('stat-acordes',  d.acordes);
      animarContador('stat-notas',    d.notas);
    })
    .catch(() => {
      // Si falla la conexión, deja el "—" sin romper la página
    });
}

function animarContador(id, total) {
  const el = document.getElementById(id);
  if (!el) return;
  const duracion = 800;
  const inicio = performance.now();

  function paso(ahora) {
    const progreso = Math.min((ahora - inicio) / duracion, 1);
    // Ease-out: desacelera al final
    const valor = Math.floor(progreso * (1 - progreso * 0.3) * total * 1.3);
    el.textContent = Math.min(valor, total);
    if (progreso < 1) requestAnimationFrame(paso);
    else el.textContent = total;
  }

  requestAnimationFrame(paso);
}

// Llama la función al cargar la página
document.addEventListener('DOMContentLoaded', cargarStats);