/*!
 * Immediately Invoked Function Expression Boilerplate
 * (c) 2019 Chris Ferdinandi, MIT License, https://gomakethings.com
 */
import { MenuPositioning } from './nav-position.js';

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

  document.addEventListener('DOMContentLoaded', function() {
    var wrapper = document.querySelector('.header-search-wrapper');
    var searchBlock = document.querySelector('.header-search-wrapper > .wp-block-search');
    var searchBlockInput = searchBlock.querySelector('.wp-block-search__input');
    var searchButton = searchBlock.querySelector('.wp-block-search__button');
    var isMouseDownInside = false;
    var isSearchVisible = false;
    searchButton.removeAttribute('type');
  
    function showSearchBlock() {
        searchBlockInput.style.display = 'block';
        searchBlock.classList.add('active');
        isSearchVisible = true;
        searchBlockInput.focus();
    }
  
    function hideSearchBlock() {
        searchBlockInput.style.display = 'none';
        searchBlock.classList.remove('active');
        isSearchVisible = false;
    }
  
    // Handle search button click
    searchButton.addEventListener('click', function(event) {
        if (!isSearchVisible) {
            event.preventDefault();
            showSearchBlock();
        }
        // If search is visible, let the default form submission happen
    });
  
    // Add mousedown event listener to the wrapper to check if the mousedown started inside the wrapper
    wrapper.addEventListener('mousedown', function(event) {
        isMouseDownInside = true;
    });
  
    // Add mouseup event listener to the document to hide the search block when clicking outside the wrapper
    document.addEventListener('mouseup', function(event) {
        if (!wrapper.contains(event.target) && !isMouseDownInside) {
            hideSearchBlock();
        }
        // Reset the flag after processing the click
        isMouseDownInside = false;
    });
  });
	// Initialize menu positioning when DOM is ready
	document.addEventListener('DOMContentLoaded', () => MenuPositioning.init());
  
})();

