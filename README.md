
<p align="center">
    <img src="https://img.shields.io/github/license/clevada/clevada" alt="license">
</p>

# Clevada: #1 Free Website Builder for Businesses, Communities, Teams and Personal Websites

### [Clevada](https://clevada.com) is a free suite for businesses, communities, teams, collaboration or personal websites. Create a free and professional website in minutes.

Clevada Suite intends to be **the best free alternative** to popular CMS and Business suites like Wordpress, Joomla, Drupal, WooCommerce, Shopify, Magento...

### Core features:
- **Template Builder**: You don't need to install or buy any template. Build your own template without any development knowledge. Manage navigation menus, sidebars, footer, homepage content and more. Manage fonts, colors, content blocks, layout style and more.
- **Multi-lingual**: Create a multi-lingual website with unlimited languages. Make translations directly in admin area. Each language will have own site version with different content, permalinks, SEO settings, locale settings, currencies and more.
- **Content blocks**: Add content blocks in static pages, posts, homepage. For a multi-language website, you can manage different content for each language.
- **SEO**: Out of the box SEO & SEF. You dont' need any SEO plugin. Manage any SEO setting for any content. Create XML sitemap. Different SEO settings for each language, including permalinks, titles, meta tags, and more.
- **Menu management**: Create navigation menu (with multiple navigation lines), manage logos, menu links, dropdowns, search form, footer navigation and much more.
- **Powerful admin area**: Manage any content and settings directly from admin area.
- **Cache management**: Speed up your website with caching.
- **User Management**: Manage internal (staff) users of your site, create departments, assign internal users to roles (to manage only assigned modules content).
- **Flexible**: Activate only needed modules to create any type of website, from a simple blog, to a complex business website, intranet or community website.
- **Responsive**: Clevada uses latest Bootstrap framework for clean and responsive designs. Template builder will generate a full responsive template, optimized for speed.
- **Developer friendly**: Clevada follows the best development practices, code is optimised for SEO, security and performance. Clevada Suite is build using the latest Laravel framework. Clevada use Bootstrap framework to build clean and full responsive templates.

### Modules:
- **CMS (Content Management)**: blog, posts, pages, content blocks, sliders...
- **Community Forum**: Create a community for your website
- **Help Desk**: Support Tickets, Knowledge Base, FAQ page, Contact Page Manager...
- **E-Commerce**: Sell downloadable products, services, phisical products. (will be released in December, 2021)
- **CRM / Productivity**: Tasks and Projects management (will be released in December, 2021)
- **Email Marketing**: Create email campaigns and send bulk emails to customers (will be released in December, 2021)
- **Landing pages**: Create landing pages for your services or products and integrate in email marketing campaigns.
- many other modules in development (Live Chat, Bookings, Intranet...)

### Content blocks:
Add unlimited content blocks on any page. Easily drag and drop content blocks and create page sections with multiple block types.
For a multi-language website, you can manage different content for each language.
- **Text / HTML Block**: add content text block with HTML editor
- **Image Block**: Add image or banner
- **Gallery Block**: Create a images gallery with multiple images
- **Video Block**: Add videos from any source, like Youtube, Vimeo and more.
- **Ads Block**: Create content blocks with ads, like Google AdSense.
- **Hero Block**: Hero unit blocks are large visual elements that are meant to grab attention and showcase key content.
- **Slider (Carousel) Block**: A slideshow component for cycling through elements—images or slides of text—like a carousel.
- **Accordion Block**: Build vertically collapsing accordions with different content.
- **Allert Block**: Provide contextual feedback messages for typical user actions with the handful of available and flexible alert messages.
- **Maps Block**: Add a Google Map section in any page.
- **Blockquote Block**: Add quoting blocks of content from another source within your document. 
- **Download Block**: Add a file download. Manage file versions, count downloads and more.
- **Reviews Block**: Add a reviews section with clients feedback, star rating and more.
- **Code Block**: Add multiple lines of code. 
- **Pricing Table Block**: Add a pricing table for your products or services.
- **Custom Block**: Add a custom block with custom code (developer mode).

### Widget blocks (dynamic content):
Add a widget block on any page or sidebar with dynamic content from any section of wour website.
- **Posts Widget Block**: add content from posts (latest posts, top posts, posts categories and more).
- **E-Commerce Widget Block**: add content from e-commerce section (latest products, top products, feature products or categories).
- **Forum Widget Block**: add content from community (latest topics, latest posts, top content and more).

---

## Clevada Hosted
**You can use our hosting service to host your domain and Clevada Suite. We install and configure Clevada Suite for free unsing your own domain. You will have full access to hosting account. More details: [Clevada Cloud Hosted](https://clevada.com/hosted).**. 

## Installation
Your hosting must have Composer and give you shell access (SSH) and ftp access outside your "public" folder.

1. ``composer create-project clevada/clevada clevada``

This will **download latest version** of Clevada Suite on your server.
The last argument ("clevada") is the root folder where application will be installed. You can use any folder name (must not exists).
**It is strongly recommended to install the suite in a folder outside your public folder**.

2. Go to "clevada' (or folder name where you download thge suite). **Edit '.env' file and set your app name, app url and database credentials**.

3. ``cd clevada``

(change directorty to your folder where you download the suite)

4. ``php artisan install``

This will **install and setup** your suite: create tables, add core data into tables, create administrator account.
You will be prompted to set administrator credentials (name, email and password).

5. **Move folders and files inside "public" folder to your server public folder** (for example: "public_html" if you use Apache Web Server).

*Note: If you have access to server configuration, you can keep "public" folder inside your application folder but you must configure your web server to directs all requests to your application's public/index.php file.*

6. **Give write access** (chmod 777) to this folders:
- Inside your application folder: "**/bootstrap/cache**" and all folders inside "**/storage**" folder.
- Inside your public folder: "**/uploads**" folder.

7. (optional). If you have modules that require cron jobs (eCommerce module for example), you must setup cron job in your hosting account to run every minute. More details:  [Setup Laravel Cron](https://laravel.com/docs/8.x/scheduling#running-the-scheduler).

**Note: You can use our hosted service to host your domain and Clevada Suite. We install and configure Clevada Suite for free unsing your own domain. More details: [Clevada Cloud Hosted](https://clevada.com/hosted).**. 

## License
Clevada is open-sourced software licensed under the [GPL-3.0 License](https://opensource.org/licenses/GPL-3.0).

## Author
Clevada is owned and developed by Chimilevschi Iosif-Gabriel

