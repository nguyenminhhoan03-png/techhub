<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" 
    xmlns:html="http://www.w3.org/TR/REC-html40"
    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml" lang="vi">
      <head>
        <title>XML Sitemap — TechHub Pro</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
          body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #1e293b;
            background: #f8fafc;
            margin: 0;
            padding: 2rem;
          }
          .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
          }
          .header {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: #ffffff;
            padding: 2rem 2.5rem;
          }
          .header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
          }
          .header p {
            margin: 0;
            font-size: 0.95rem;
            color: #bfdbfe;
            line-height: 1.5;
          }
          .stats-bar {
            background: #f1f5f9;
            padding: 1rem 2.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            font-weight: 600;
            color: #475569;
          }
          .stats-bar span strong {
            color: #2563eb;
          }
          table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.88rem;
          }
          th {
            background: #f8fafc;
            padding: 0.85rem 1.25rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
          }
          td {
            padding: 0.85rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
          }
          tr:hover td {
            background: #f8fafc;
          }
          a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            word-break: break-all;
          }
          a:hover {
            text-decoration: underline;
          }
          .badge-priority {
            display: inline-block;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.78rem;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
          }
          .badge-freq {
            display: inline-block;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            font-size: 0.78rem;
            background: #f1f5f9;
            color: #475569;
          }
          .footer {
            padding: 1.25rem 2.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            background: #fafafa;
          }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h1>🗺️ Sơ Đồ Trang Web XML Sitemap</h1>
            <p>Tự động sinh bởi TechHub SEO Engine theo tiêu chuẩn Sitemaps.org Protocol 0.9 dành cho Google &amp; Bing Search Console.</p>
          </div>
          <div class="stats-bar">
            <span>Tổng số liên kết (URLs): <strong><xsl:value-of select="count(sitemap:urlset/sitemap:url)"/></strong></span>
            <span>Định dạng: <strong>XML 1.0 (sitemaps.org)</strong></span>
          </div>
          <table>
            <thead>
              <tr>
                <th style="width: 50px;">#</th>
                <th>Địa Chỉ Liên Kết (URL)</th>
                <th style="width: 100px;">Độ Ưu Tiên</th>
                <th style="width: 120px;">Tần Suất</th>
                <th style="width: 160px;">Cập Nhật Gần Nhất</th>
              </tr>
            </thead>
            <tbody>
              <xsl:for-each select="sitemap:urlset/sitemap:url">
                <tr>
                  <td style="color: #94a3b8;"><xsl:value-of select="position()"/></td>
                  <td>
                    <a href="{sitemap:loc}" target="_blank">
                      <xsl:value-of select="sitemap:loc"/>
                    </a>
                  </td>
                  <td>
                    <span class="badge-priority">
                      <xsl:value-of select="sitemap:priority"/>
                    </span>
                  </td>
                  <td>
                    <span class="badge-freq">
                      <xsl:value-of select="sitemap:changefreq"/>
                    </span>
                  </td>
                  <td style="color: #64748b; font-size: 0.82rem;">
                    <xsl:value-of select="sitemap:lastmod"/>
                  </td>
                </tr>
              </xsl:for-each>
            </tbody>
          </table>
          <div class="footer">
            TechHub Pro — Sơ đồ trang web được tạo tự động và tối ưu hóa thời gian thực.
          </div>
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
