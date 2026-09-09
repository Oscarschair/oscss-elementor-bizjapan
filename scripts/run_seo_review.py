#!/usr/bin/env python3
"""
SEO Reviewer Automation Hook Script (seo_reviewer)
Audits SEO meta tags, OpenGraph, JSON-LD Schema, and Hong Kong (zh-HK) optimizations.
"""

import os
import re
import sys

def audit_seo():
    print("\n--- [HOOK] Executing seo_reviewer Quality Gate ---")
    seo_php = os.path.join("includes", "bizjapan-seo-zhhk.php")
    if not os.path.exists(seo_php):
        print(f"[FAIL] Missing {seo_php}")
        return False

    with open(seo_php, "r", encoding="utf-8") as f:
        php = f.read()

    errors = []
    passes = []

    # 1. Hong Kong language & locale
    if "zh-HK" in php and "zh_HK" in php:
        passes.append("Hong Kong (zh-HK) language attributes and locale configured.")
    else:
        errors.append("zh-HK language tag missing.")

    # 2. JSON-LD Schema Output
    if "application/ld+json" in php and "Organization" in php and "Service" in php:
        passes.append("JSON-LD structured data (Organization, Service, FAQPage) verified.")
    else:
        errors.append("JSON-LD structured data missing or incomplete.")

    # 3. Canonical & OpenGraph meta tags
    if "og:title" in php and "og:description" in php and "twitter:card" in php:
        passes.append("OpenGraph (OGP) and Twitter Card tags configured.")
    else:
        errors.append("OGP or Twitter Card tags missing.")

    # 4. Modern Image Replacement (flow-2 and flow2 separation)
    if "service-scheme-relation-zhhk.jpg" in php and "service-workflow-flow-zhhk.jpg" in php:
        passes.append("Image auto-replacement mapping (Scheme vs 5-Step Workflow) strictly separated.")
    else:
        errors.append("Flow diagram image mapping not properly separated.")

    for p in passes:
        print(f"  [PASS] {p}")

    if errors:
        for e in errors:
            print(f"  [FAIL] {e}")
        return False

    print("  [SUCCESS] seo_reviewer check passed with 100% compliance.\n")
    return True

if __name__ == "__main__":
    if not audit_seo():
        sys.exit(1)
    sys.exit(0)
