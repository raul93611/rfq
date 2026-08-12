/* Items & Services table redesign — kebab menus, description expand/collapse,
   comment popovers, subitem show/hide. Pure interaction layer over markup
   rendered by RepositorioItem/RepositorioSubitem/ServiceRepository — every
   action button here keeps its original class + data attributes, so the
   existing delegated handlers in quote.js/services.js still fire unchanged.
   Delegated on document since rows are replaced wholesale on every AJAX
   refresh (refreshItemsTable() / refreshServicesSection()). */
$(document).ready(function () {

  // Menus/popovers are position:fixed and placed here via getBoundingClientRect,
  // rather than position:absolute anchored in-flow off the trigger. The table's
  // horizontal-scroll wrapper (.it-table-scroll) sets overflow-x:auto, which
  // per spec flips overflow-y from visible to auto too — an in-flow absolute
  // popover would get clipped for rows near the table's bottom edge, and one
  // that renders past the table's right edge would inflate the wrapper's
  // scrollable width and pop a horizontal scrollbar. Fixed positioning opts
  // out of both since it isn't part of any ancestor's overflow box.
  function positionFloating($el, $trigger) {
    var margin = 8;
    var triggerRect = $trigger[0].getBoundingClientRect();

    $el.css({ top: 0, left: 0, right: 'auto', bottom: 'auto' }).removeAttr('hidden');
    var elRect = $el[0].getBoundingClientRect();

    var left = triggerRect.right - elRect.width; // right-align to the trigger by default
    if (left < margin) left = triggerRect.left;
    if (left + elRect.width > window.innerWidth - margin) left = window.innerWidth - elRect.width - margin;
    if (left < margin) left = margin;

    var top = triggerRect.bottom + 5;
    if (top + elRect.height > window.innerHeight - margin) {
      top = triggerRect.top - elRect.height - 5;
    }
    if (top < margin) top = margin;

    $el.css({ top: top, left: left }).data('it-trigger', $trigger);
  }

  // Keep any open menu/popover anchored to its trigger button on scroll,
  // rather than closing it — a fixed-position element doesn't move with the
  // page, so without this it would visually drift away from its button.
  function repositionOpenFloaters() {
    $('.it-menu, .it-note-pop').each(function () {
      var $el = $(this);
      if ($el.attr('hidden') !== undefined) return;
      var $trigger = $el.data('it-trigger');
      if ($trigger && $trigger.length && document.body.contains($trigger[0])) {
        positionFloating($el, $trigger);
      } else {
        $el.attr('hidden', true);
      }
    });
  }

  function closeAllMenus(except) {
    $('.it-menu').not(except || []).each(function () {
      var $menu = $(this);
      if ($menu.attr('hidden') !== undefined) return;
      $menu.attr('hidden', true);
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
      closeAllMenus();
      return;
    }

    positionFloating($menu, $btn);
    $btn.addClass('is-open');
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

    if (isOpen) {
      closeAllNotes();
      return;
    }

    positionFloating($pop, $btn);
    $btn.addClass('is-open');
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
  // Capture phase so this also fires for scrolls inside nested containers
  // (e.g. .it-table-scroll), not just the window.
  document.addEventListener('scroll', repositionOpenFloaters, true);

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
