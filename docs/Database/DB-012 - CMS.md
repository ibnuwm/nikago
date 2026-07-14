# DB-012 - CMS

## Tujuan

Mengelola seluruh konten website Nikago.

---

## Daftar Tabel

- pages
- page_sections
- menus
- menu_items
- banners
- faqs
- blog_categories
- blog_tags
- blogs
- blog_comments
- media

---

# Table : pages

Halaman statis.

Contoh

- Home
- About
- Contact
- Pricing
- Privacy Policy
- Terms

---

# Table : page_sections

Section Landing Page.

Contoh

Hero

Feature

Pricing

FAQ

CTA

---

# Table : menus

Navigasi website.

---

# Table : menu_items

Detail menu.

---

# Table : banners

Banner promosi.

---

# Table : faqs

Frequently Asked Questions.

---

# Table : blogs

Artikel blog.

Kolom utama

- title
- slug
- excerpt
- content
- author_id
- published_at
- seo_title
- seo_description

---

# Table : blog_categories

Kategori blog.

---

# Table : blog_tags

Tag artikel.

---

# Table : media

Media Library.

Digunakan oleh

- Blog
- CMS
- Invitation
- Vendor
- Gallery

---

## Business Rules

- Slug unik.
- Draft dapat diedit.
- Publish hanya Admin/Marketing.
- Media disimpan di Cloudflare R2.

---

## Future Improvement

- AI Blog Writer
- Versioning
- Revision History
- Drag & Drop Builder
