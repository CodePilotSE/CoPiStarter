// Menu positioning module
export const MenuPositioning = {
  init() {
    this.menuItems = document.querySelectorAll('.menu-item-has-children');
    this.setupEventListeners();
    this.recalculateAllSubmenus();
  },

  setupEventListeners() {
    // Mouseover events
    this.menuItems.forEach(item => {
      item.addEventListener('mouseover', () => this.handleMenuHover(item));
    });

    // Resize event with debounce
    window.addEventListener('resize', this.debounce(() => this.recalculateAllSubmenus(), 250));
  },

  handleMenuHover(menuItem) {
    const submenu = menuItem.querySelector('.sub-menu');
    if (submenu) {
      this.adjustSubmenuPosition(submenu);
    }
  },

  recalculateAllSubmenus() {
    this.menuItems.forEach(item => {
      const submenu = item.querySelector('.sub-menu');
      if (submenu) {
        submenu.classList.remove('left', 'left-nudged');
        this.adjustSubmenuPosition(submenu);
      }
    });
  },

  adjustSubmenuPosition(submenu) {
    const { rect, windowWidth, isNested, parentIsLeft, parentIsLeftNudged } = this.getSubmenuInfo(submenu);
    
    if (rect.right > windowWidth) {
      submenu.classList.add(isNested ? 'left' : 'left-nudged');
    } else if (parentIsLeft || parentIsLeftNudged) {
      submenu.classList.add('left');
    }

    // Handle nested submenus
    submenu.querySelectorAll('.sub-menu').forEach(nestedSubmenu => {
      this.adjustSubmenuPosition(nestedSubmenu);
    });
  },

  getSubmenuInfo(submenu) {
    return {
      rect: submenu.getBoundingClientRect(),
      windowWidth: window.innerWidth,
      isNested: submenu.parentElement.parentElement.classList.contains('sub-menu'),
      parentIsLeft: submenu.parentElement.parentElement.classList.contains('left'),
      parentIsLeftNudged: submenu.parentElement.parentElement.classList.contains('left-nudged')
    };
  },

  debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
};