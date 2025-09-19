// Theme management
function initTheme() {
  const theme = localStorage.getItem('theme') || 'light';
  document.documentElement.setAttribute('data-theme', theme);
  updateThemeToggle(theme);
}

function toggleTheme() {
  const currentTheme = document.documentElement.getAttribute('data-theme');
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', newTheme);
  localStorage.setItem('theme', newTheme);
  updateThemeToggle(newTheme);
}

function updateThemeToggle(theme) {
  const toggle = document.querySelector('.theme-toggle');
  if (toggle) {
    toggle.innerHTML = theme === 'dark' ? '<i class="fa-solid fa-sun"></i>' : '<i class="fa-solid fa-moon"></i>';
  }
}

// Tab management
function initTabs() {
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabContents = document.querySelectorAll('.tab-content');

  tabButtons.forEach((button, index) => {
    button.addEventListener('click', () => {
      // Remove active class from all tabs
      tabButtons.forEach(btn => btn.classList.remove('active'));
      tabContents.forEach(content => content.classList.remove('active'));
      
      // Add active class to clicked tab
      button.classList.add('active');
      if (tabContents[index]) {
        tabContents[index].classList.add('active');
      }
    });
  });
}

// Modal management
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('active');
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('active');
  }
}

// Search functionality
function initSearch() {
  const searchInputs = document.querySelectorAll('.search-input');
  
  searchInputs.forEach(input => {
    input.addEventListener('input', (e) => {
      const searchTerm = e.target.value.toLowerCase();
      const table = e.target.closest('.table-container').querySelector('tbody');
      const rows = table.querySelectorAll('tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });
  });
}

// Sidebar mobile toggle
function initSidebar() {
  const sidebarToggle = document.querySelector('.sidebar-toggle');
  const sidebar = document.querySelector('.sidebar');
  
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });
  }
}

// Active menu item
function setActiveMenuItem() {
  const path = window.location.pathname;
  const menuItems = document.querySelectorAll('.menu-item');
  
  menuItems.forEach(item => {
    const href = item.getAttribute('href');
    if (href && path.endsWith(href)) {
      item.classList.add('active');
    }
  });
}

// File upload preview
function initFileUpload() {
  const fileInputs = document.querySelectorAll('input[type="file"]');
  
  fileInputs.forEach(input => {
    const container = input.closest('.file-upload');
    
    input.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          showImagePreview(container, e.target.result);
        };
        reader.readAsDataURL(file);
      }
    });
    
    // Drag and drop
    container.addEventListener('dragover', (e) => {
      e.preventDefault();
      container.classList.add('dragover');
    });
    
    container.addEventListener('dragleave', () => {
      container.classList.remove('dragover');
    });
    
    container.addEventListener('drop', (e) => {
      e.preventDefault();
      container.classList.remove('dragover');
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        input.files = files;
        input.dispatchEvent(new Event('change'));
      }
    });
  });
}

function showImagePreview(container, src) {
  const existing = container.querySelector('.preview-image');
  if (existing) existing.remove();
  
  const preview = document.createElement('img');
  preview.src = src;
  preview.className = 'preview-image';
  preview.style.cssText = 'width: 200px; height: 200px; margin-top: 10px; border-radius: 50%;';
  container.appendChild(preview);
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  initTabs();
  initSearch();
  initSidebar();
  setActiveMenuItem();
  initFileUpload();
  
  // Theme toggle event
  const themeToggle = document.querySelector('.theme-toggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', toggleTheme);
  }
  
  // Modal close events
  document.querySelectorAll('.modal-close, .modal-overlay').forEach(element => {
    element.addEventListener('click', (e) => {
      if (e.target === element) {
        const modal = element.closest('.modal-overlay');
        if (modal) {
          modal.classList.remove('active');
        }
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
    // Revenue Chart
    fetch('/api/revenue-chart')
        .then(response => response.json())
        .then(data => {
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        });

    // Top Games Chart
    fetch('/api/top-games-chart')
        .then(response => response.json())
        .then(data => {
            new Chart(document.getElementById('topGamesChart'), {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        });
});