/* Items & Services table redesign moved row actions (Edit/Delete/Add Provider/etc.)
   behind a per-row kebab (⋮) menu — the buttons still carry their original classes
   and data attributes, they're just hidden until the kebab is opened. Call this
   before clicking any row action button that used to sit directly in the row. */
async function openKebabFor(page, actionSelector, index = 0) {
  const action = page.locator(actionSelector).nth(index);
  const wrap = action.locator('xpath=ancestor::div[contains(concat(" ", normalize-space(@class), " "), " it-kebab-wrap ")][1]');
  await wrap.locator('.it-kebab').click();
  return action;
}

module.exports = { openKebabFor };
