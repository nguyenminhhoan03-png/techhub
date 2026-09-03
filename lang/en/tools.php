<?php

declare(strict_types=1);

return [
    'common' => [
        'online_tool' => 'online tools',
        'free_online_tools' => 'free online tools, developer utilities, TechHub',
        'home_meta_keywords' => 'TechHub, online programming tools, json formatter, jwt debugger, regex tester, base64, SEO tools, free online tools, developer utilities, free online games',
        'tools_index_meta_title' => 'All Free Online Tools — Developer Utilities, Calculators & SEO | TechHub',
        'tools_index_meta_desc' => 'More than :count free programming and online utilities: JSON Formatter, JWT Debugger, Base64, Regex Tester, SEO Tools, Calculator, and more. No registration required.',
        'tools_index_meta_keywords' => 'online programming tools, json formatter, jwt debugger, base64 encode decode, regex tester, developer tools, SEO tools, free online tools, TechHub',
    ],
    'json-formatter' => [
        'name' => 'JSON Formatter & Validator',
        'summary' => 'Format, beautify, minify, and validate JSON data instantly with customizable indentation and error highlighting.',
        'description_markdown' => '## 📌 Online JSON Formatter & Validator

**JSON (JavaScript Object Notation)** is the industry-standard data interchange format for modern web development, mobile apps, and RESTful APIs. Raw JSON transmitted across networks is often minified into a single line or contains subtle syntax errors that are hard to spot with the naked eye.

TechHub’s **JSON Formatter** helps developers and DevOps teams:
* **Beautify & Structure**: Automatically format nested objects and arrays with custom indentation (2 spaces or 4 spaces).
* **Syntax Validation**: Highlight syntax errors immediately with clear error descriptions.
* **Minification**: Compress JSON data into a compact single-line string to optimize bandwidth.
* **Ultra-Fast & Private**: Process data in-memory under 5ms with **Zero Data Retention** (no inputs are ever stored).

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Paste your raw JSON payload into the input box (or click **Load Sample JSON** to test).
2. **Step 2**: Select your preferred action (Beautify, Minify, or Validate Only) and indent size.
3. **Step 3**: Click **Run Tool Now** to process your data instantly.
4. **Step 4**: Click **Copy Result** to copy the formatted output to your clipboard.

---

## ❓ Frequently Asked Questions (FAQ)

### 1. Why does my JSON report a syntax error?
Common reasons include unquoted keys, single quotes instead of double quotes (`"`), or trailing commas at the end of objects and arrays.

### 2. Is it safe to paste confidential tokens or API keys here?
Yes, 100%. TechHub processes all computations transiently in server memory and never persists your data to any storage.

### 3. Can it handle large JSON payloads?
Yes, the engine is optimized with high-performance stream parsing to format large JSON structures without browser freezing.',
        'meta_title' => 'JSON Formatter & Validator Online — 100% Free | TechHub',
        'meta_description' => 'Format, beautify, minify, and validate JSON data instantly with customizable indentation and error highlighting.',
        'ui' => [
            'input_label' => 'JSON String to Process',
            'btn_load_sample' => 'Load Sample JSON',
            'action_label' => 'Action',
            'action_beautify' => 'Beautify (Format)',
            'action_minify' => 'Minify (Compact)',
            'action_validate' => 'Validate Only',
            'indent_label' => 'Indentation',
            'indent_2_spaces' => '2 Spaces',
            'indent_4_spaces' => '4 Spaces',
        ],
    ],
    'base64-encode-decode' => [
        'name' => 'Base64 Encoder & Decoder',
        'summary' => 'Encode and decode text strings or binary files to and from Base64 format with full RFC 4648 and URL-safe support.',
        'description_markdown' => '## 📌 RFC 4648 Base64 Converter

**Base64** represents binary data in an ASCII string format by translating it into a radix-64 representation. It is widely used in email transfer via MIME, embedding images in HTML/CSS (Data URI), and passing authentication tokens.

TechHub’s **Base64 Tool** provides:
* **Encode**: Convert plain UTF-8 text strings into standard or URL-safe Base64 strings.
* **Decode**: Restore Base64 strings back to clean, human-readable text.
* **URL-Safe RFC 4648**: Automatically replace `+` with `-` and `/` with `_` for safe URL query parameters.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Enter or paste your text or Base64 string into the input area.
2. **Step 2**: Choose **Encode to Base64** or **Decode from Base64**.
3. **Step 3**: (Optional) Check the **URL-Safe** checkbox if the output will be used in a URL query.
4. **Step 4**: Click **Run Tool Now** and copy the converted output.

---

## ❓ Frequently Asked Questions (FAQ)

### 1. Is Base64 an encryption algorithm?
No. Base64 is an encoding scheme, not encryption. Anyone can decode a Base64 string without a password. Never store sensitive passwords using Base64 alone.

### 2. What does the `=` padding character mean?
Padding characters (`=`) ensure that the Base64 output length is a multiple of 4 bytes when the binary input is not divisible by 3.',
        'meta_title' => 'Base64 Encoder & Decoder Online — 100% Free | TechHub',
        'meta_description' => 'Encode and decode text strings or binary files to and from Base64 format with full RFC 4648 and URL-safe support.',
        'ui' => [
            'input_label' => 'Text String or Base64 to Process',
            'btn_load_sample' => 'Load Sample Text',
            'input_placeholder' => 'Enter plain text or Base64 string...',
            'action_encode' => 'Encode to Base64',
            'action_decode' => 'Decode from Base64',
            'opt_url_safe' => 'URL-Safe (RFC 4648)',
        ],
    ],
    'hash-generator' => [
        'name' => 'Cryptographic Hash Generator',
        'summary' => 'Generate secure checksums and hashes including MD5, SHA-1, SHA-256, SHA-512, and Bcrypt with optional HMAC key support.',
        'description_markdown' => '## 📌 Cryptographic Hash & Checksum Generator

A **cryptographic hash function** is a mathematical algorithm that maps data of arbitrary size to a fixed-size bit string. It is a one-way function designed to be impossible to invert.

TechHub supports all essential hashing algorithms:
* **MD5 & SHA-1**: Fast checksums for verifying file integrity.
* **SHA-256 & SHA-512**: Modern NIST standard cryptographic hashes for security certificates and blockchain verification.
* **Bcrypt**: Adaptive salted password hashing algorithm.
* **HMAC**: Hash-based message authentication using an optional shared secret key.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Enter the text or password you want to hash into the input area.
2. **Step 2**: Select a specific algorithm or choose "All Algorithms" for a side-by-side comparison.
3. **Step 3**: (Optional) Enter an HMAC secret key if required.
4. **Step 4**: Click **Generate Hashes Now** to compute cryptographic digests instantly.

---

## ❓ Frequently Asked Questions (FAQ)

### 1. Can a SHA-256 hash be decrypted?
No. Cryptographic hashes are one-way functions. The only way to reverse them is via brute force or dictionary lookups against precomputed tables.

### 2. Which algorithm is best for password storage?
Use **Bcrypt** or **Argon2** for passwords. Never store user passwords in MD5 or raw SHA-256.',
        'meta_title' => 'Cryptographic Hash Generator Online — 100% Free | TechHub',
        'meta_description' => 'Generate secure checksums and hashes including MD5, SHA-1, SHA-256, SHA-512, and Bcrypt with optional HMAC key support.',
        'ui' => [
            'input_label' => 'Text String to Hash (Input string)',
            'btn_load_sample' => 'Load Sample',
            'input_placeholder' => 'Enter text string or password to hash...',
            'algorithm_label' => 'Hash Algorithm',
            'alg_all' => 'All Algorithms (MD5, SHA1, SHA256, SHA512, Bcrypt)',
            'alg_sha256' => 'SHA-256 (Recommended for Security)',
            'alg_md5' => 'MD5 Checksum',
            'alg_sha1' => 'SHA-1',
            'alg_sha512' => 'SHA-512',
            'alg_bcrypt' => 'Bcrypt Password Hash',
            'secret_label' => 'HMAC Secret Key (Optional)',
            'secret_placeholder' => 'Leave blank if not using HMAC...',
            'btn_submit' => 'Generate Hashes Now',
        ],
    ],
    'jwt-debugger' => [
        'name' => 'JWT Debugger & Decoder',
        'summary' => 'Decode and inspect JSON Web Tokens (JWT) headers, payload claims, signature algorithms, and token expiration times.',
        'description_markdown' => '## 📌 JWT Token Inspector & Decoder

**JSON Web Tokens (JWT)** are an open RFC 7519 standard for securely transmitting information between parties as a compact JSON object.

Paste any JWT into TechHub’s debugger to immediately view:
* **JOSE Header**: Algorithm (`alg`) and Token Type (`typ`).
* **Payload Claims**: User ID (`sub`), Issuer (`iss`), Expiration Time (`exp`), Issued At (`iat`).
* **Token Expiration Status**: Clear countdown and validity badge showing if the token is currently active or expired.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Paste your JWT token into the text area.
2. **Step 2**: Click **Decode & Inspect Token**.
3. **Step 3**: Review the decoded Header, Payload claims, and expiration diagnostics in the interactive visual breakdown.

---

## ❓ Frequently Asked Questions (FAQ)

### 1. Does TechHub store my decoded JWT tokens?
No. All decoding is processed locally in RAM and zero data is persisted to disk or databases.',
        'meta_title' => 'JWT Debugger & Decoder Online — 100% Free | TechHub',
        'meta_description' => 'Decode and inspect JSON Web Tokens (JWT) headers, payload claims, signature algorithms, and token expiration times.',
        'ui' => [
            'input_label' => 'JSON Web Token (JWT)',
            'btn_load_sample' => 'Load Sample JWT',
            'btn_submit' => 'Decode & Inspect Token',
        ],
    ],
    'regex-tester' => [
        'name' => 'Regex Tester & Match Extractor',
        'summary' => 'Test PCRE regular expressions in real-time with regex flag toggles, captured groups breakdown, and instant error debugging.',
        'description_markdown' => '## 📌 Regular Expression (PCRE) Debugger

Test and validate PCRE regular expressions against test strings in real-time.

* **PCRE Compliant**: Matches PHP, JavaScript, and Python regex behavior.
* **Flag Support**: Easily configure `g` (global), `i` (case-insensitive), `m` (multiline), and `s` (dotAll).
* **Capture Groups Extraction**: Inspect numbered and named capture groups with character positions.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Enter your regular expression pattern between the forward slashes.
2. **Step 2**: Configure desired flags (`g`, `i`, `m`).
3. **Step 3**: Paste your target test string into the text area.
4. **Step 4**: Click **Test Regex Matches** to view all extracted match groups and indexes.

---

## ❓ Frequently Asked Questions (FAQ)

### 1. What does the "g" flag do?
The `g` (global) flag ensures all occurrences in the string are matched rather than stopping at the very first match.',
        'meta_title' => 'Regex Tester & Match Extractor Online — 100% Free | TechHub',
        'meta_description' => 'Test PCRE regular expressions in real-time with regex flag toggles, captured groups breakdown, and instant error debugging.',
        'ui' => [
            'pattern_label' => 'Regular Expression Pattern',
            'btn_load_sample' => 'Load Email Regex Sample',
            'flags_help' => 'Common flags: g (global), i (case-insensitive), m (multiline), s (dotAll)',
            'test_text_label' => 'Test String',
            'test_text_placeholder' => 'Enter text to test regex matches against...',
            'sample_test_text' => 'Contact us at contact@techhub.vn or admin@techhub.local for support.',
            'btn_submit' => 'Test Regex Matches',
        ],
    ],
    'url-encoder-decoder' => [
        'name' => 'URL Encoder & Decoder',
        'summary' => 'Encode special characters and query strings according to RFC 3986 and decode percent-encoded URLs safely.',
        'description_markdown' => '## 📌 RFC 3986 URL Percent Encoder & Decoder

Convert unsafe ASCII characters, spaces, and non-ASCII Unicode characters into percent-encoded strings (`%20`), or decode encoded URLs back to normal text.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Enter the URL string or query parameters into the input box.
2. **Step 2**: Select **Encode URL** or **Decode URL**.
3. **Step 3**: Choose between standard RFC 3986 (`%20`) or legacy space encoding (`+`).
4. **Step 4**: Click **Run Tool Now** to generate your URL string.',
        'meta_title' => 'URL Encoder & Decoder Online — 100% Free | TechHub',
        'meta_description' => 'Encode special characters and query strings according to RFC 3986 and decode percent-encoded URLs safely.',
        'ui' => [
            'input_label' => 'URL String or Query Parameters',
            'btn_load_sample' => 'Load Sample URL',
            'action_encode' => 'Encode URL',
            'action_decode' => 'Decode URL',
            'standard_label' => 'Standard:',
        ],
    ],
    'loan-calculator' => [
        'name' => 'Loan & Mortgage Amortization Calculator',
        'summary' => 'Calculate Equated Monthly Installments (EMI), total interest payable, loan breakdown, and monthly amortization schedules.',
        'description_markdown' => '## 📌 Financial Loan & EMI Mortgage Calculator

Calculate home mortgage, car financing, or personal loan repayments with international banking precision using the standard reducing balance EMI formula.

* **Monthly EMI**: Clear installment calculation per month.
* **Interest vs. Principal Split**: Visual breakdown of total interest payable versus original borrowing.
* **Full Amortization Schedule**: Month-by-month table of starting balance, interest, principal, and ending balance.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Enter the total loan principal amount.
2. **Step 2**: Enter the annual interest rate (e.g. 8.5%).
3. **Step 3**: Enter the loan tenure in months (e.g. 60 months = 5 years).
4. **Step 4**: Click **Calculate Monthly Payment (EMI)** to view the repayment schedule.

---

## ❓ Frequently Asked Questions (FAQ)

### 1. What is an EMI?
EMI stands for Equated Monthly Installment — a fixed payment amount made by a borrower to a lender at a specified date each calendar month.',
        'meta_title' => 'Loan & Mortgage Amortization Calculator Online — 100% Free | TechHub',
        'meta_description' => 'Calculate Equated Monthly Installments (EMI), total interest payable, loan breakdown, and monthly amortization schedules.',
        'ui' => [
            'principal_label' => 'Loan Amount (Principal)',
            'principal_hint' => 'Example: 500,000,000',
            'rate_label' => 'Annual Interest Rate (%/year)',
            'rate_hint' => 'Example: 8.5%',
            'term_label' => 'Loan Term (Months)',
            'term_hint' => '(60 months = 5 years)',
            'btn_submit' => 'Calculate Monthly Payment (EMI)',
        ],
    ],
    'percentage-calculator' => [
        'name' => 'Percentage & Discount Calculator',
        'summary' => 'Calculate percentage changes, proportion values, discount savings, and rapid percentage ratios in real-time.',
        'description_markdown' => '## 📌 Quick Percentage Calculator

Quickly solve common percentage problems:
1. **What is X% of Y?** (e.g., 20% discount on a $500 product).
2. **X is what % of Y?** (e.g., What percentage is 25 out of 200).
3. **Percentage Increase / Decrease**: Growth rate calculation between two periods.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Choose your calculation mode.
2. **Step 2**: Enter Value A and Value B.
3. **Step 3**: Click **Calculate Now** to see the resulting percentage and ratio breakdown.',
        'meta_title' => 'Percentage & Discount Calculator Online — 100% Free | TechHub',
        'meta_description' => 'Calculate percentage changes, proportion values, discount savings, and rapid percentage ratios in real-time.',
        'ui' => [
            'mode_label' => 'Percentage Calculation Mode',
            'mode_percent_of' => '1. What is X% of Y? (e.g. 20% of 500,000)',
            'mode_is_what_percent' => '2. X is what % of Y? (e.g. 25 of 200)',
            'mode_increase_decrease' => '3. Percentage increase or decrease from A to B',
            'val_a_label' => 'Value A (X or Initial Value)',
            'val_b_label' => 'Value B (Y or New Value)',
            'btn_submit' => 'Calculate Now',
        ],
    ],
    'bmi-calculator' => [
        'name' => 'BMI & Body Weight Calculator',
        'summary' => 'Calculate Body Mass Index (BMI), identify health categories based on WHO international standards, and find ideal weight ranges.',
        'description_markdown' => '## 📌 Health BMI & Body Weight Calculator

**Body Mass Index (BMI)** is a screening metric used by the World Health Organization (WHO) to classify body weight categories in adults:
* **Underweight**: BMI < 18.5
* **Normal weight**: BMI 18.5 – 24.9
* **Overweight**: BMI 25.0 – 29.9
* **Obese**: BMI ≥ 30.0

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Choose your unit system (Metric: cm, kg or Imperial: inches, lbs).
2. **Step 2**: Enter your height and weight.
3. **Step 3**: Click **Evaluate BMI Index** to view your score, classification badge, and healthy weight range.',
        'meta_title' => 'BMI & Body Weight Calculator Online — 100% Free | TechHub',
        'meta_description' => 'Calculate Body Mass Index (BMI), identify health categories based on WHO international standards, and find ideal weight ranges.',
        'ui' => [
            'unit_label' => 'Measurement System',
            'unit_metric' => 'Metric Standard (cm, kg)',
            'unit_imperial' => 'Imperial Standard (inches, lbs)',
            'height_label' => 'Height',
            'weight_label' => 'Weight',
            'btn_submit' => 'Evaluate BMI Index',
        ],
    ],
    'image-metadata-inspector' => [
        'name' => 'Image EXIF & Metadata Inspector',
        'summary' => 'Inspect image pixel dimensions, aspect ratio, color depth, MIME formats, and camera EXIF metadata in-browser.',
        'description_markdown' => '## 📌 Image EXIF & Metadata Inspector

Inspect image resolution, aspect ratio, color depth, and camera EXIF metadata (camera model, exposure, focal length, ISO, aperture) directly in your browser with complete privacy.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Click the dropzone or drag and drop an image file (JPG, PNG, WEBP, GIF).
2. **Step 2**: Click **Inspect Image Metadata Now**.
3. **Step 3**: Review detailed image properties and camera parameters.',
        'meta_title' => 'Image EXIF & Metadata Inspector Online — 100% Free | TechHub',
        'meta_description' => 'Inspect image pixel dimensions, aspect ratio, color depth, MIME formats, and camera EXIF metadata in-browser.',
        'ui' => [
            'upload_label' => 'Upload image to inspect metadata & EXIF',
            'dropzone_title' => 'Click to choose image file',
            'dropzone_desc' => 'or drag & drop image file directly here (JPG, PNG, WEBP, GIF)',
            'btn_submit' => 'Inspect Image Metadata Now',
        ],
    ],
    'image-color-extractor' => [
        'name' => 'Dominant Color Palette Extractor',
        'summary' => 'Automatically extract dominant colors and generate harmonious color palettes with HEX, RGB, and HSL codes from uploaded images.',
        'description_markdown' => '## 📌 Image Color Palette Extractor

Upload any photo, UI mockup, or digital graphic to extract prominent dominant colors with exact HEX, RGB, and HSL values for design workflows.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Upload a photo or UI screenshot.
2. **Step 2**: Select the number of palette colors to extract (3, 5, 8, or 10 swatches).
3. **Step 3**: Click **Extract Color Palette**.
4. **Step 4**: Click on any color swatch card to copy its HEX code instantly to your clipboard.',
        'meta_title' => 'Dominant Color Palette Extractor Online — 100% Free | TechHub',
        'meta_description' => 'Automatically extract dominant colors and generate harmonious color palettes with HEX, RGB, and HSL codes from uploaded images.',
        'ui' => [
            'upload_label' => 'Upload image to extract color palette',
            'dropzone_title' => 'Click to choose design photo',
            'dropzone_desc' => 'or drag & drop image file here (100% private in-memory processing)',
            'size_label' => 'Number of Palette Colors',
            'opt_5' => '5 dominant colors',
            'opt_3' => '3 primary colors',
            'opt_8' => '8 color swatches',
            'opt_10' => '10 color swatches',
            'btn_submit' => 'Extract Color Palette',
        ],
    ],
    'serp-preview' => [
        'name' => 'Google SERP Snippet Preview Tool',
        'summary' => 'Simulate desktop and mobile Google Search results, measure pixel widths, and optimize SEO title and meta description lengths.',
        'description_markdown' => '## 📌 Google SERP Snippet Preview & Pixel Meter

Simulate exact Google Search result listings on desktop and mobile screens. Prevent truncation with live pixel width checks and character counters.

* **Pixel Width Checks**: Measures against Google’s ~600px desktop title limit and ~960px meta description limit.
* **Device Switcher**: Toggle between Desktop and Mobile preview cards.
* **Rich Snippet Badges**: Preview rating stars, review counts, and publication dates.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Enter your webpage title, description, and canonical URL.
2. **Step 2**: Configure simulated device, review rating, and publish date.
3. **Step 3**: Click **Preview SERP Snippet Now** to view Google search rendering.

---

## ❓ Frequently Asked Questions (FAQ)

### 1. What is the recommended title tag length for Google?
Between 50 and 60 characters (~600 pixels width). Longer titles will be truncated by Google with an ellipsis (`...`).',
        'meta_title' => 'Google SERP Snippet Preview Tool Online — 100% Free | TechHub',
        'meta_description' => 'Simulate desktop and mobile Google Search results, measure pixel widths, and optimize SEO title and meta description lengths.',
        'ui' => [
            'title_label' => 'SEO Page Title',
            'btn_load_sample' => 'Load Sample SERP',
            'title_placeholder' => 'e.g. Complete Guide to Onpage SEO Optimization 2026 — TechHub',
            'title_sample' => 'Complete Guide to Onpage SEO Optimization 2026 — TechHub',
            'title_hint' => 'Recommended: 50 - 60 chars (~600 px)',
            'chars_unit' => 'chars',
            'desc_label' => 'Meta Description',
            'desc_placeholder' => 'Enter compelling summary containing focus keywords to maximize search CTR...',
            'desc_sample' => 'Discover comprehensive onpage SEO techniques compliant with Google: Meta tags optimization, Schema JSON-LD, XML Sitemaps, and ultrafast loading speed.',
            'desc_hint' => 'Recommended: 120 - 160 chars (~960 px)',
            'url_label' => 'Webpage URL',
            'site_name_label' => 'Website Name (Optional)',
            'site_name_sample' => 'TechHub Global',
            'device_label' => 'Device Simulation',
            'device_desktop' => '💻 Desktop',
            'device_mobile' => '📱 Mobile',
            'date_label' => 'Published Date',
            'rating_val_label' => 'Star Rating (Rich Snippet)',
            'rating_cnt_label' => 'Review Count',
            'btn_submit' => 'Preview SERP Snippet Now',
        ],
    ],
    'meta-tag-generator' => [
        'name' => 'HTML5 Meta Tag Generator (SEO Onpage)',
        'summary' => 'Generate complete HTML5 meta tags: Title, Description, Keywords, Canonical URLs, and Robots directives for optimal SEO.',
        'description_markdown' => '## 📌 HTML5 SEO Meta Tag Generator

Generate clean, production-ready `<head>` tags including basic meta tags, robots indexing directives, and canonical URLs.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Fill in page title, description, keywords, canonical URL, author, and language.
2. **Step 2**: Configure Robots meta directives (Index/Noindex, Follow/Nofollow, Noarchive, Nosnippet).
3. **Step 3**: Click **Generate HTML5 Meta Tags Now**.
4. **Step 4**: Copy the generated `<head>` code directly into your HTML template.',
        'meta_title' => 'HTML5 Meta Tag Generator (SEO Onpage) Online — 100% Free | TechHub',
        'meta_description' => 'Generate complete HTML5 meta tags: Title, Description, Keywords, Canonical URLs, and Robots directives for optimal SEO.',
        'ui' => [
            'title_label' => 'Page Title (Title Tag)',
            'btn_load_sample' => 'Load Sample Meta',
            'title_placeholder' => 'Webpage title...',
            'title_sample' => 'TechHub - All-in-One Online Developer Tools & SEO Suite',
            'desc_label' => 'Page Description (Meta Description)',
            'desc_placeholder' => 'Concise summary of page content...',
            'desc_sample' => 'TechHub provides 20+ free online developer tools and SEO utilities: JSON Formatter, Regex Tester, Base64, Schema Generator, SERP Preview.',
            'keywords_label' => 'Keywords (Meta Keywords)',
            'keywords_sample' => 'developer tools, seo tools, json formatter, schema generator, techhub',
            'canonical_label' => 'Canonical URL',
            'author_label' => 'Author / Organization',
            'author_sample' => 'TechHub Engineering Team',
            'language_label' => 'Page Language (ISO Code)',
            'robots_label' => 'Robots Meta Directives',
            'opt_index' => 'Index (Allow Indexing)',
            'opt_noindex' => 'Noindex (Disallow Indexing)',
            'opt_follow' => 'Follow (Crawl Links)',
            'opt_nofollow' => 'Nofollow (Do Not Crawl)',
            'opt_noarchive' => 'Noarchive (Do Not Cache)',
            'opt_nosnippet' => 'Nosnippet (No Snippet Preview)',
            'btn_submit' => 'Generate HTML5 Meta Tags Now',
        ],
    ],
    'schema-generator' => [
        'name' => 'Google Schema (JSON-LD) Generator',
        'summary' => 'Create Google Rich Results structured data (Schema.org JSON-LD): Article, LocalBusiness, FAQPage, Product, and Breadcrumbs.',
        'description_markdown' => '## 📌 Schema.org JSON-LD Structured Data Builder

Generate valid Schema.org JSON-LD structured data for Google Search to unlock Rich Snippets (star ratings, FAQ accordions, product pricing, and business info).

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Select the structured data schema type (Article, FAQPage, Product, LocalBusiness, BreadcrumbList, SoftwareApplication, Organization).
2. **Step 2**: Fill in the relevant entity details.
3. **Step 3**: Click **Generate Schema JSON-LD Now**.
4. **Step 4**: Copy the `<script type="application/ld+json">` tag and paste into your webpage `<head>`.',
        'meta_title' => 'Google Schema (JSON-LD) Generator Online — 100% Free | TechHub',
        'meta_description' => 'Create Google Rich Results structured data (Schema.org JSON-LD): Article, LocalBusiness, FAQPage, Product, and Breadcrumbs.',
        'ui' => [
            'type_label' => 'Schema Structured Data Type',
            'btn_load_sample' => 'Load Sample Schema',
            'type_article' => '📰 Article / BlogPosting (News, Blog Post)',
            'type_faq' => '❓ FAQPage (Frequently Asked Questions)',
            'type_product' => '🛍️ Product (Product, Pricing, Reviews)',
            'type_local' => '🏢 LocalBusiness (Local Store, Branch, Clinic)',
            'type_breadcrumb' => '🧭 BreadcrumbList (Breadcrumb Hierarchy)',
            'type_software' => '💻 SoftwareApplication (Software / Web App)',
            'type_org' => '🌐 Organization (Company, Brand Entity)',
            'headline_label' => 'Headline / Entity Name',
            'headline_sample' => '10 Ways to Optimize Website Speed with Clean Architecture',
            'desc_label' => 'Summary Description',
            'desc_sample' => 'Step-by-step guide to refactoring source code and implementing caching to reduce response latency below 5ms.',
            'url_label' => 'Article / Webpage URL',
            'image_url_label' => 'Thumbnail Image URL',
            'author_label' => 'Author Name',
            'publisher_label' => 'Publisher Name',
            'faq_label' => 'FAQ Content (FAQPage only: Q: / A: per line)',
            'faq_placeholder' => "Q: Question 1?\nA: Answer 1...\nQ: Question 2?\nA: Answer 2...",
            'faq_sample' => "Q: What is the benefit of Schema JSON-LD in SEO?\nA: It helps Google understand content structure and display rich snippets in search results.\nQ: Does TechHub support free Schema generation?\nA: All structured data tools on TechHub are 100% free with no registration required.",
            'btn_submit' => 'Generate Schema JSON-LD Now',
        ],
    ],
    'open-graph-generator' => [
        'name' => 'Open Graph & Social Cards Generator',
        'summary' => 'Create and preview social sharing cards for Facebook Open Graph, Twitter/X Cards, LinkedIn, and messaging platforms.',
        'description_markdown' => '## 📌 Open Graph & Social Card Generator

Optimize preview thumbnails, titles, and descriptions when sharing web pages across Facebook, Twitter/X, LinkedIn, WhatsApp, and Telegram.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Enter the social share title, description, and thumbnail image URL (1200x630px recommended).
2. **Step 2**: Configure your canonical URL, site name, OG type, and Twitter card format.
3. **Step 3**: Click **Generate & Preview Social Cards** to inspect the live preview card.',
        'meta_title' => 'Open Graph & Social Cards Generator Online — 100% Free | TechHub',
        'meta_description' => 'Create and preview social sharing cards for Facebook Open Graph, Twitter/X Cards, LinkedIn, and messaging platforms.',
        'ui' => [
            'title_label' => 'Social Share Title (OG Title)',
            'btn_load_sample' => 'Load Sample OG',
            'title_placeholder' => 'Title displayed when sharing link...',
            'title_sample' => 'TechHub — All-in-One Online Developer Tools & SEO Suite',
            'desc_label' => 'Social Share Description (OG Description)',
            'desc_placeholder' => 'Summary excerpt displayed on social feeds...',
            'desc_sample' => 'Experience 20+ ultrafast developer utilities, financial calculators, and onpage SEO tools with zero data retention.',
            'image_label' => 'Thumbnail Image URL (Recommended 1200x630px)',
            'url_label' => 'Canonical URL',
            'site_name_label' => 'Website Name (Site Name)',
            'type_label' => 'Open Graph Type (OG Type)',
            'type_website' => 'website (Homepage / Category)',
            'type_article' => 'article (Blog / News Article)',
            'type_product' => 'product (Commercial Product)',
            'twitter_card_label' => 'Twitter / X Card Type',
            'card_large' => 'summary_large_image (Large Image)',
            'card_summary' => 'summary (Small Square Image)',
            'twitter_site_label' => 'Twitter / X Account',
            'btn_submit' => 'Generate & Preview Social Cards',
        ],
    ],
    'robots-txt-generator' => [
        'name' => 'Robots.txt Generator & Validator',
        'summary' => 'Generate and validate SEO-friendly robots.txt rules, block AI bots, configure crawl delays, and link XML sitemaps.',
        'description_markdown' => '## 📌 Robots.txt Generator & AI Scraper Blocker

Configure search crawler permissions, block AI scrapers (GPTBot, CCBot, Claude-Web, PerplexityBot), and create custom rules for WordPress, Laravel, or custom apps.

* **SEO Presets**: Quick templates for Standard SEO, Allow All, Disallow All, and AI Blocking.
* **AI Bot Defense**: Protect intellectual property by blocking automated LLM web crawlers.
* **Sitemap Integration**: Directly link your XML sitemap URL.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Select a configuration preset from the dropdown (or keep SEO Standard Default).
2. **Step 2**: Add or customize paths to Disallow and Allow.
3. **Step 3**: Verify your XML Sitemap URL and optional crawl delay.
4. **Step 4**: Keep AI bot blocking enabled to protect site content.
5. **Step 5**: Click **Generate Robots.txt Now** to inspect and download your `robots.txt` file.

---

## ❓ Frequently Asked Questions (FAQ)

### 1. Where should the robots.txt file be uploaded?
Upload it directly to your website’s root directory so it is accessible at `https://yourdomain.com/robots.txt`.

### 2. Can robots.txt hide sensitive files?
No. Robots.txt is publicly viewable by anyone. Never use it to hide sensitive file paths.',
        'meta_title' => 'Robots.txt Generator & Validator Online — 100% Free | TechHub',
        'meta_description' => 'Generate and validate SEO-friendly robots.txt rules, block AI bots, configure crawl delays, and link XML sitemaps.',
        'ui' => [
            'preset_label' => 'Configuration Presets',
            'btn_load_sample' => 'Load Standard Preset',
            'preset_default' => '⚡ SEO Standard Configuration (Default)',
            'preset_allow_all' => '🟢 Allow All Bots (Allow All)',
            'preset_block_all' => '🔴 Disallow All Bots (Development Mode)',
            'preset_block_ai' => '🛡️ Block All AI Crawlers (OpenAI, Anthropic, CCBot)',
            'preset_wordpress' => '🌐 WordPress Optimized',
            'preset_laravel' => '🚀 Laravel Web App Optimized',
            'disallow_label' => 'Paths to BLOCK (One Disallow entry per line)',
            'allow_label' => 'Paths to ALLOW (One Allow entry per line)',
            'sitemap_label' => 'Sitemap XML File URL',
            'delay_label' => 'Crawl-delay (Seconds - Optional)',
            'delay_placeholder' => 'Leave blank if not needed',
            'block_ai_label' => '🛡️ Automatically block AI Crawlers & Scrapers (GPTBot, CCBot, Claude-Web, PerplexityBot)',
            'btn_submit' => 'Generate Robots.txt Now',
        ],
    ],
    'sitemap-generator' => [
        'name' => 'XML Sitemap Generator & Validator',
        'summary' => 'Generate and validate valid Sitemaps.org XML sitemaps with priority, change frequency, and last modification attributes.',
        'description_markdown' => '## 📌 Online XML Sitemap Generator

Create valid `sitemap.xml` files for Google Search Console and Bing Webmaster Tools with priority weights and change frequency settings.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Enter your website domain in the crawler input bar.
2. **Step 2**: (Optional) Open **Advanced Settings** to configure default frequency, priority, and max URL limit.
3. **Step 3**: Click **Generate Sitemap** to crawl URLs and generate valid XML.
4. **Step 4**: Download the `sitemap.xml` file and upload it to your website root.',
        'meta_title' => 'XML Sitemap Generator & Validator Online — 100% Free | TechHub',
        'meta_description' => 'Generate and validate valid Sitemaps.org XML sitemaps with priority, change frequency, and last modification attributes.',
        'ui' => [
            'hero_title' => 'Better Indexing Starts Here',
            'hero_desc' => 'Automatically crawl your website and generate valid XML Sitemap compliant with Google, Bing & Sitemaps.org. Fast, 100% free with no registration required.',
            'domain_placeholder' => 'Your Website Domain (e.g. https://example.com)...',
            'btn_generate' => 'Generate Sitemap',
            'btn_advanced' => '⚙️ Advanced Settings ▾',
            'freq_label' => 'Change Frequency (Changefreq)',
            'freq_daily' => 'daily (Every Day)',
            'freq_weekly' => 'weekly (Every Week)',
            'freq_monthly' => 'monthly (Every Month)',
            'freq_always' => 'always (Continuously)',
            'freq_hourly' => 'hourly (Every Hour)',
            'priority_label' => 'Default Priority',
            'priority_10' => '1.0 (Homepage / Highest)',
            'priority_08' => '0.8 (Articles / Detail Pages)',
            'priority_06' => '0.6 (Secondary / Tools Pages)',
            'priority_05' => '0.5 (Contact / About Pages)',
            'max_urls_label' => 'Max Crawl URLs',
            'opt_50' => '50 URLs',
            'opt_100' => '100 URLs',
            'opt_250' => '250 URLs',
            'opt_500' => '500 URLs',
            'lastmod_label' => 'Automatically include modification date (Lastmod)',
            'btn_manual_mode' => '✍️ Manual URL Input Mode',
            'manual_label' => 'Custom URL List (If not using crawler)',
            'badge_free' => '⚡ 100% Free',
            'badge_crawler' => '🕷️ Live Web Crawler',
            'badge_standards' => '🔍 Google & Bing Compliant',
            'badge_export' => '📄 Export sitemap.xml Instantly',
        ],
    ],
    'slug-generator' => [
        'name' => 'SEO URL Slug Generator (Stop Word Filter)',
        'summary' => 'Convert accented text and titles into clean, search-engine-friendly URL slugs with automatic stop word filtering.',
        'description_markdown' => '## 📌 SEO-Friendly URL Slug Converter

Transform article titles and strings into clean, search-engine-friendly hyphenated URL slugs, automatically stripping stop words to keep URLs concise and keyword-rich.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Enter your article title or text into the input area.
2. **Step 2**: Select your preferred separator (hyphen or underscore) and letter casing.
3. **Step 3**: Enable stop word filtering to remove filler words.
4. **Step 4**: Click **Generate SEO URL Slug** and copy the slug for your CMS or web framework.',
        'meta_title' => 'SEO URL Slug Generator (Stop Word Filter) Online — 100% Free | TechHub',
        'meta_description' => 'Convert accented text and titles into clean, search-engine-friendly URL slugs with automatic stop word filtering.',
        'ui' => [
            'input_label' => 'Article Title / Text to Convert to Slug',
            'btn_load_sample' => 'Load Sample Title',
            'input_placeholder' => 'Enter article title with accents or special characters...',
            'input_sample' => 'Comprehensive Guide to Onpage SEO Optimization for Websites in 2026!',
            'separator_label' => 'Separator',
            'sep_hyphen' => '- Hyphen (Google Standard)',
            'sep_underscore' => '_ Underscore (Snake case)',
            'case_label' => 'Letter Case Format',
            'max_len_label' => 'Maximum Length (Chars)',
            'stop_words_label' => '⚡ Automatically filter stop words (and, is, of, for, in, on, the...) for concise, SEO-friendly URLs',
            'btn_submit' => 'Generate SEO URL Slug',
        ],
    ],
    'proxy-checker' => [
        'name' => 'Proxy Checker (HTTP, HTTPS, SOCKS4, SOCKS5)',
        'summary' => 'Check live/dead status, latency ping, exit IP, country, and anonymity levels for single or bulk proxy lists.',
        'description_markdown' => '## 📌 Multi-Protocol Online Proxy Checker

Test HTTP, HTTPS, SOCKS4, and SOCKS5 proxy availability in real-time. Measure latency ping in milliseconds, detect exit IP geolocation, and verify anonymity levels.

---

## 🛠️ Step-by-Step Usage Guide

1. **Step 1**: Paste up to 20 proxies into the text area (one proxy per line).
2. **Step 2**: Select protocol (Auto Detect, HTTP, SOCKS5, or SOCKS4) and connection timeout.
3. **Step 3**: Click **Check Proxy List Now**.
4. **Step 4**: Inspect the live status table and copy all verified active proxies.',
        'meta_title' => 'Proxy Checker (HTTP, HTTPS, SOCKS4, SOCKS5) Online — 100% Free | TechHub',
        'meta_description' => 'Check live/dead status, latency ping, exit IP, country, and anonymity levels for single or bulk proxy lists.',
        'ui' => [
            'input_label' => 'Proxy List to Check (1 per line, max 20 proxies)',
            'btn_load_sample' => 'Load Sample Proxies',
            'btn_clear' => 'Clear',
            'supported_formats' => 'Supported formats:',
            'protocol_label' => 'Test Protocol',
            'protocol_auto' => '⚡ Auto Detect (Recommended)',
            'protocol_http' => '🌐 HTTP / HTTPS Proxy',
            'protocol_socks5' => '🧦 SOCKS5 Proxy',
            'protocol_socks4' => '🔌 SOCKS4 Proxy',
            'timeout_label' => 'Connection Timeout',
            'timeout_3s' => '⚡ 3 seconds (Fast Scan)',
            'timeout_5s' => '⏱️ 5 seconds (Standard)',
            'timeout_10s' => '⏳ 10 seconds (Slow / Far Proxies)',
            'help_security' => '🛡️ Directly tests exit IP, GeoIP location, ISP, and anonymity level.',
            'btn_submit' => 'Check Proxy List Now',
        ],
    ],
];
