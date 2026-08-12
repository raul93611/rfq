/* Items & Services table redesign — kebab menus, description expand/collapse,
   comment popovers, subitem show/hide. Pure interaction layer over markup
   rendered by RepositorioItem/RepositorioSubitem/ServiceRepository — every
   action button here keeps its original class + data attributes, so the
   existing delegated handlers in quote.js/services.js still fire unchanged.
   Delegated on document since rows are replaced wholesale on every AJAX
   refresh (refreshItemsTable() / refreshServicesSection()). */
$(document).ready(function () {

  function closeAllMenus(except) {
    $('.it-menu').not(except || []).each(function () {
      var $menu = $(this);
      if ($menu.attr('hidden') !== undefined) return;
      $menu.attr('hidden', true).removeClass('is-up');
      $menu.closest('.it-kebab-wrap').find('.it-kebab').removeClass('is-open');
    });
  }

  function closeAllNotes(except) {
    $('.it-note-pop').not(except || []).each(function () {
      var $pop = $(this);
      if ($pop.attr('hidden') !== undefined) return;
      $pop.attr('hidden', true);
      $pop.closest('.it-note').find('.it-note-btn').removeClass('is-open');
    });
  }

  /* ---------- Kebab row-action menu ---------- */
  $(document).on('click', '.it-kebab', function (e) {
    e.stopPropagation();
    var $btn = $(this);
    var $menu = $btn.closest('.it-kebab-wrap').find('.it-menu');
    var isOpen = $menu.attr('hidden') === undefined;

    closeAllMenus($menu);
    closeAllNotes();

    if (isOpen) {
      $menu.attr('hidden', true).removeClass('is-up');
      $btn.removeClass('is-open');
      return;
    }

    $menu.removeAttr('hidden').removeClass('is-up');
    $btn.addClass('is-open');

    var rect = $menu[0].getBoundingClientRect();
    if (rect.bottom > window.innerHeight) {
      $menu.addClass('is-up');
    }
  });

  // Any click on an actual action item closes its menu right away so it
  // doesn't linger open behind the modal the action just opened.
  $(document).on('click', '.it-menu-item', function () {
    closeAllMenus();
  });

  /* ---------- Comment popover ---------- */
  $(document).on('click', '[data-toggle-note]', function (e) {
    e.stopPropagation();
    var $btn = $(this);
    var $pop = $btn.closest('.it-note').find('.it-note-pop');
    var isOpen = $pop.attr('hidden') === undefined;

    closeAllNotes($pop);
    closeAllMenus();

    $pop.attr('hidden', isOpen ? true : false);
    $btn.toggleClass('is-open', !isOpen);
  });

  /* ---------- Dismiss open menus/popovers on outside click or Escape ---------- */
  $(document).on('click', function () {
    closeAllMenus();
    closeAllNotes();
  });
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') {
      closeAllMenus();
      closeAllNotes();
    }
  });

  /* ---------- Description block — condensed by default, expands in place ---------- */
  $(document).on('click', '[data-toggle-desc]', function () {
    $(this).toggleClass('is-open');
  });
  $(document).on('keydown', '[data-toggle-desc]', function (e) {
    if (e.key !== 'Enter' && e.key !== ' ') return;
    e.preventDefault();
    $(this).toggleClass('is-open');
  });

  /* ---------- Subitem show/hide ---------- */
  $(document).on('click', '[data-toggle-subitems]', function (e) {
    e.stopPropagation();
    var $btn = $(this);
    var itemId = $btn.data('toggle-subitems');
    var showing = $btn.hasClass('is-open');

    $btn.toggleClass('is-open', !showing).attr('title', showing ? 'Show subitems' : 'Hide subitems');
    $('tr[data-parent-item="' + itemId + '"]').attr('hidden', showing);
  });
});
