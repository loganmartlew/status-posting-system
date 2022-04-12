const btn = document.querySelector('.menubutton');
const menu = document.querySelector('.menu');

const modals = document.querySelectorAll('.modal');
const modalContents = document.querySelectorAll('.modal-content');

const openMenu = () => {
  menu.classList.remove('hidden');
  menu.classList.remove('content-hidden');
};

const closeMenu = () => {
  const menu = document.querySelector('.menu');

  menu.classList.add('content-hidden');
  setTimeout(() => {
    menu.classList.add('hidden');
  }, 500);
};

btn.addEventListener('click', () => {
  if (menu.classList.contains('hidden')) {
    openMenu();
    return;
  }

  if (!menu.classList.contains('hidden')) {
    closeMenu();
    return;
  }
});

modals.forEach(modal => {
  modal.addEventListener('click', () => {
    console.log('close');
    closeMenu();
  });
});

modalContents.forEach(content => {
  content.addEventListener('click', e => {
    e.stopPropagation();
  });
});
