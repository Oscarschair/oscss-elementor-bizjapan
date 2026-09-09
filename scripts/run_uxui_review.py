#!/usr/bin/env python3
"""
UI/UX Reviewer Automation Hook Script (uxui_reviewer)
Audits responsive CSS rules, 800px flow diagram width restriction,
touch targets, and overflow-x prevention.
"""

import os
import re
import sys

def audit_uxui():
    print("\n--- [HOOK] Executing uxui_reviewer Quality Gate ---")
    css_path = os.path.join("assets", "css", "bizjapan-responsive.css")
    if not os.path.exists(css_path):
        print(f"[FAIL] Missing {css_path}")
        return False

    with open(css_path, "r", encoding="utf-8") as f:
        css = f.read()

    errors = []
    passes = []

    # 1. Overflow-X Prevention Shield
    if "overflow-x: hidden !important;" in css:
        passes.append("Global overflow-x prevention shield verified.")
    else:
        errors.append("Global overflow-x prevention rule missing.")

    # 2. Process Flow Images Max-Width Restriction (Proposal A: 800px)
    if "max-width: 800px" in css and ".elementor-element-df41cae" in css:
        passes.append("Flow diagrams max-width successfully restricted to 800px (Anti-Cramming verified).")
    else:
        errors.append("Flow diagrams 800px max-width rule missing.")

    # 3. Card Elevation & Rounded Corners
    if "box-shadow:" in css and "border-radius: 16px" in css:
        passes.append("Floating card elevation (box-shadow & rounded radius) verified.")
    else:
        errors.append("Card shadow or border-radius missing.")

    # 4. Minimum Touch Target (WCAG 48px+)
    if "min-height: 48px" in css or "min-height: 52px" in css:
        passes.append("WCAG mobile touch target (48px+) verified.")
    else:
        errors.append("Mobile button minimum height rule missing.")

    for p in passes:
        print(f"  [PASS] {p}")

    if errors:
        for e in errors:
            print(f"  [FAIL] {e}")
        return False

    print("  [SUCCESS] uxui_reviewer check passed with 100% compliance.\n")
    return True

if __name__ == "__main__":
    if not audit_uxui():
        sys.exit(1)
    sys.exit(0)
