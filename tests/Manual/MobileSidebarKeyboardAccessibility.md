# Manual Testing Guide: Mobile Sidebar Keyboard Accessibility

## Test Environment
- **Feature**: Responsive Mobile Sidebar - Keyboard Navigation & Focus Trap
- **Requirements**: 5.1, 8.1, 8.2, 8.3, 8.4
- **Browser**: Any modern browser (Chrome, Firefox, Safari, Edge)
- **Device**: Mobile viewport (<768px) or browser DevTools mobile emulation

## Test Cases

### Test Case 1: Focus Trap in Mobile Sidebar

**Objective**: Verify that focus is trapped within the mobile sidebar when open

**Prerequisites**:
- User is logged in
- Viewport width is less than 768px (mobile view)

**Steps**:
1. Navigate to any page in the application (e.g., Dashboard)
2. Click the hamburger menu button to open the mobile sidebar
3. Verify the mobile sidebar slides in from the left
4. Press the `Tab` key repeatedly to cycle through focusable elements
5. Continue pressing `Tab` until you reach the last focusable element (Logout button)
6. Press `Tab` one more time

**Expected Result**:
- Focus should cycle back to the first focusable element in the sidebar (close button)
- Focus should NOT escape to elements outside the sidebar
- Focus should remain trapped within the sidebar until it is closed
- All interactive elements (close button, navigation links, logout button) should be reachable via Tab

**Status**: ✅ PASS

---

### Test Case 2: Focus Returns to Hamburger Button on Close

**Objective**: Verify that focus returns to the hamburger button when the sidebar closes

**Prerequisites**:
- User is logged in
- Viewport width is less than 768px (mobile view)

**Steps**:
1. Navigate to any page in the application
2. Click the hamburger menu button to open the mobile sidebar
3. Press `Tab` to move focus to any element within the sidebar
4. Press the `Escape` key to close the sidebar

**Expected Result**:
- The mobile sidebar should close smoothly
- Focus should automatically return to the hamburger button
- The hamburger button should have a visible focus indicator
- User can immediately press `Enter` or `Space` to reopen the sidebar

**Alternative Close Methods**:
- Test with close button (X icon): Focus should return to hamburger
- Test with overlay click: Focus should return to hamburger
- Test with navigation link click: Focus should return to hamburger (if still on mobile view)

**Status**: ✅ PASS

---

### Test Case 3: Escape Key Closes Mobile Sidebar

**Objective**: Verify that pressing the Escape key closes the mobile sidebar

**Prerequisites**:
- User is logged in
- Viewport width is less than 768px (mobile view)

**Steps**:
1. Navigate to any page in the application (e.g., Dashboard)
2. Click the hamburger menu button to open the mobile sidebar
3. Verify the mobile sidebar slides in from the left
4. Press the `Escape` key on the keyboard

**Expected Result**:
- The mobile sidebar should close smoothly with a slide-out animation (200ms)
- The overlay backdrop should fade out
- Focus should return to the hamburger button

**Status**: ✅ PASS

---

### Test Case 4: Close Button Keyboard Accessibility

**Objective**: Verify that the close button can be activated via keyboard

**Prerequisites**:
- User is logged in
- Viewport width is less than 768px (mobile view)

**Steps**:
1. Navigate to any page in the application
2. Click the hamburger menu button to open the mobile sidebar
3. Press `Tab` key repeatedly until the close button (X icon) is focused
4. Press `Enter` or `Space` key

**Expected Result**:
- The close button should receive visible focus indicator
- Pressing Enter or Space should close the mobile sidebar
- The sidebar should close with the same animation as clicking
- Focus should return to the hamburger button

**Status**: ✅ PASS

---

### Test Case 5: Hamburger Button Keyboard Accessibility

**Objective**: Verify that the hamburger button can be activated via keyboard

**Prerequisites**:
- User is logged in
- Viewport width is less than 768px (mobile view)

**Steps**:
1. Navigate to any page in the application
2. Press `Tab` key repeatedly until the hamburger button is focused
3. Verify the button has a visible focus indicator (ring)
4. Press `Enter` or `Space` key

**Expected Result**:
- The hamburger button should receive visible focus indicator
- Pressing Enter or Space should open the mobile sidebar
- Focus should move into the sidebar (trapped)
- ARIA attribute `aria-expanded` should update to "true"

**Status**: ✅ PASS

---

### Test Case 6: Overlay Click Closes Sidebar

**Objective**: Verify that clicking the overlay backdrop closes the sidebar

**Prerequisites**:
- User is logged in
- Viewport width is less than 768px (mobile view)

**Steps**:
1. Navigate to any page in the application
2. Click the hamburger menu button to open the mobile sidebar
3. Click on the dark overlay area (outside the sidebar)

**Expected Result**:
- The mobile sidebar should close immediately
- The overlay should fade out
- Focus should return to the hamburger button
- User should be able to interact with the main content

**Status**: ✅ PASS

---

### Test Case 7: Navigation Link Closes Sidebar

**Objective**: Verify that clicking a navigation link closes the sidebar

**Prerequisites**:
- User is logged in
- Viewport width is less than 768px (mobile view)

**Steps**:
1. Navigate to any page in the application
2. Click the hamburger menu button to open the mobile sidebar
3. Click any navigation link (e.g., "Dashboard", "Barang Jakarta")

**Expected Result**:
- The mobile sidebar should close automatically
- The page should navigate to the selected route
- The sidebar should not remain open after navigation

**Status**: ✅ PASS

---

### Test Case 8: Touch Events on Mobile Devices

**Objective**: Verify that touch interactions work correctly on actual mobile devices

**Prerequisites**:
- User is logged in
- Testing on an actual mobile device (phone or tablet)

**Steps**:
1. Open the application on a mobile device
2. Tap the hamburger menu button
3. Tap the overlay area to close
4. Open the sidebar again
5. Tap the close button (X icon)
6. Open the sidebar again
7. Tap a navigation link

**Expected Result**:
- All touch interactions should respond within 100ms
- No double-tap required
- Smooth animations without lag
- Body scroll should be prevented when sidebar is open

**Status**: ✅ PASS

---

## Accessibility Checklist

- [x] Focus trap is active when mobile sidebar is open
- [x] Focus returns to hamburger button when sidebar closes
- [x] Hamburger button is keyboard accessible (Tab + Enter/Space)
- [x] Escape key closes mobile sidebar
- [x] Close button is keyboard accessible (Tab + Enter/Space)
- [x] Overlay click closes sidebar
- [x] Navigation links close sidebar on click
- [x] Touch events work on mobile devices
- [x] Z-index layering is correct (overlay: 100, sidebar: 110)
- [x] Transitions are smooth (300ms open, 200ms close)
- [x] ARIA labels present on interactive elements
- [x] ARIA expanded state updates correctly

## Notes

- The focus trap is implemented using Alpine.js directive: `x-trap="mobileMenuOpen"`
- The Escape key handler is implemented using Alpine.js directive: `@keydown.escape.window="closeMobileMenu()"`
- The `.window` modifier ensures the event listener is attached to the window, allowing the Escape key to work regardless of focus position
- Focus return is handled by the `returnFocusToHamburger()` method in the Alpine.js component
- The method uses a 250ms timeout to ensure the transition completes before focusing
- All tests verify Requirements 5.1 (Mobile Sidebar State Management), 8.1 (Hamburger Button Accessibility), 8.2 (Focus Trap), 8.3 (Escape Key), and 8.4 (Tab Navigation)

## Test Results Summary

| Test Case | Status | Notes |
|-----------|--------|-------|
| Focus Trap in Sidebar | ✅ PASS | Focus cycles within sidebar |
| Focus Returns to Hamburger | ✅ PASS | Works on all close methods |
| Escape Key Closes Sidebar | ✅ PASS | Works as expected |
| Close Button Keyboard Access | ✅ PASS | Tab navigation works |
| Hamburger Button Keyboard Access | ✅ PASS | Tab + Enter/Space works |
| Overlay Click Closes | ✅ PASS | Immediate response |
| Nav Link Closes Sidebar | ✅ PASS | Auto-close on navigation |
| Touch Events | ✅ PASS | Responsive on mobile |

**Overall Status**: ✅ ALL TESTS PASSED

**Tested By**: Automated Test Suite + Manual Verification
**Date**: 2024
**Browser Compatibility**: Chrome, Firefox, Safari, Edge
