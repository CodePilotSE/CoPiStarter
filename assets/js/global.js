/*!
 * Immediately Invoked Function Expression Boilerplate
 * (c) 2019 Chris Ferdinandi, MIT License, https://gomakethings.com
 */
;(function () {

	'use strict';

	// Element variables
	const menuToggle = document.querySelector('.menu-toggle');
	const navMenu = document.querySelector('.nav-menu[role="navigation"]');

	const elementExists = function(element) {
		if ( typeof(element) != 'undefined' && element != null ) {
			return true;
		}
		return false;
	}

	// Event functions
	const toggleMenu = function(event) {
		if ( !event.target.closest('.menu-toggle') ) return;
		if ( elementExists(navMenu) ) {
			navMenu.classList.toggle('active');
		}
		menuToggle.classList.toggle('active');
	}

	const toggleSubMenu = function(event) {
		if ( !event.target.closest('.submenu-expand') ) return;
		event.target.closest('.submenu-expand').classList.toggle('expanded');
		event.preventDefault();
	}

	// Add functions to click event listener
	document.addEventListener('click', function(event) {
		toggleMenu(event);
		toggleSubMenu(event);
	});

})();


// Menu positioning module
const MenuPositioning = {
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

// Initialize menu positioning when DOM is ready
document.addEventListener('DOMContentLoaded', () => MenuPositioning.init());