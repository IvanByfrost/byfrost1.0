document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const openBtn = document.getElementById('openBtn');
  const closeBtn = document.getElementById('closeBtn');
  const container = document.querySelector('.guide-container');

  openBtn.addEventListener('click', () => {
    sidebar.classList.add('active');
    container.classList.add('sidebar-open');
    openBtn.style.display = 'none';
    closeBtn.style.display = 'inline-block';
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('active');
    container.classList.remove('sidebar-open');
    openBtn.style.display = 'inline-block';
    closeBtn.style.display = 'none';
  });
});