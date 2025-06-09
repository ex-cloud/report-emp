
php_laravel:
    build: ./php
    image: php:latest
    container_name: php_laravel
    hostname: "php"
    volumes:
      - ./src:/var/www/html
      - ./php/www/conf:/user/local/etc/php-fpm.d/www.conf
    working_dir: /var/www/html
    depends_on:
      - mysql_laravel
    
    mysql_laravel:
      image: mysql:latest
      container_name: mysql_laravel
      restart: unless-stopped
      ttyle: true
      ports:
        - "3306:3306"
      volumes:
        - ./database/data:/var/lib/mysql
        - ./database/conf:/etc/mysql/conf.d:ro
      environment:
        MYSQL_ROOT_PASSWORD: 123
        MYSQL_DATABASE: k2net_report_app
        MYSQL_USER: root
        MYSQL_PASSWORD: 123
        TZ: "Asia/Jakarta"
        SERVICE_TAGS: dev
        SERVICE_NAME: mysql

    nginx_laravel:
      build: ./nginx
      image: nginx:latest
      container_name: nginx_laravel
      hostname: "nginx"
      ports:
        - "80:80"
        - "443:443"
      volumes:
        - ./src:/var/www/html
        - ./nginx/conf:/etc/nginx/conf.d
        - ./nginx/ssl:/etc/nginx/ssl
      depends_on:
        - php_laravel









.fi-topbar nav {
    height: 3.5rem;
    @apply backdrop-blur-md bg-white/50;
}
.fi-topbar nav:is(.dark *) {
    height: 3rem;
    @apply backdrop-blur-md bg-[#171717]/50;
}

.fi-panel-admin {
    background-color: #fafafa !important;
}
.fi-panel-admin:is(.dark *) {
    background-color: #171717 !important;
}

.fi-ta-ctn:is(.dark *) {
    background-color: #1f1f1f !important;
}
.fi-ta-header-cell-label {
    font-size: 0.85rem;
    font-weight: bold;
    line-height: 1rem;
}
.fi-ta-text-item-label {
    font-size: 0.82rem;
    line-height: 1rem;
}
.fi-ta-text p {
    font-size: .8rem;
    line-height: 1rem;
}
.fi-ta-image {
    padding-bottom: 0.5rem;
    padding-top: 0.5rem;
}
.fi-ta-image img[style] {
    height: 2rem !important;
    width: 2rem !important;
}
.fi-input-wrp:focus-within:not(:has(.fi-ac-action:focus)):is(.dark *) {
    --tw-ring-opacity: 1;
    --tw-ring-color: rgba(101, 163, 13,var(--tw-ring-opacity,1));
}
#nprogress .bar {
    background: #10b981 !important; /* Gunakan warna Tailwind */
}
.dark #nprogress .bar {
    background: #10b981 !important; /* Warna lain saat mode gelap */
}
.fi-input-wrp:focus-within:not(:has(.fi-ac-action:focus)) {
    --tw-ring-color: #65a30d;
}
.fi-sidebar-item-icon:is(.dark *) {
    color: #65a30d;
}
.fi-dropdown-panel:is(.dark *) {
    /* background-color: #242424 !important; */ 
    @apply bg-[#242424]/30 backdrop-blur-md ring-1 ring-[hsla(0,0%,100%,.1)] !important;
}
.fi-modal-window:is(.dark *) {
    background-color: #171717 !important;
}
.fi-fo-date-time-picker-panel:is(.dark *) {
    background-color: #171717 !important;
}
.choices__list--dropdown:is(.dark *) {
    background-color: #171717 !important;
}
.fi-fo-date-time-picker-panel:is(.dark *) select {
    background-color: #171717 !important;
}
.fi-global-search-result-group:is(.dark *) {
    background-color: #171717 !important;
}
.fi-global-search-result-group:is(.dark *) div {
    background-color: #171717 !important;
    border-color: #65a30d;
}
.fi-section:is(.dark *) {
    background-color: #1f1f1f !important;
}
.fi-fo-tabs-tab:is(.dark *) {
    background-color: #1f1f1f !important;
}
.fi-tabs:is(.dark *) {
    background-color: #1f1f1f !important;
}
.fi-tabs-item-label {
    font-size: 13px;
    line-height: 1.2rem;
}
.fi-input {
    line-height: 1rem;
}
.fi-btn {
    font-size: .75rem;
    line-height: 1rem;
    --tw-ring-color: hsl(0, 0%, 84.3%);
}
.fi-btn:is(.dark *) {
    --tw-bg-opacity: 1;
    --tw-ring-color:hsl(0, 0%, 19.2%);
}
.fi-btn-label:is(.dark *) {
    font-size: .75rem;
    line-height: 1rem;
}
.fi-btn-icon:is(.dark *) {
    height: 1rem;
    width: 1rem;
}
.fi-dropdown-header-label {
    font-size: 13px;
    line-height: 1.2rem;
}
.fi-dropdown-header-icon:is(.dark *) {
    color: hsl(84.8 85.2% 34.5%);
}
.fi-theme-switcher svg {
    height: 1rem;
    width: 1rem;
}
.fi-dropdown-list-item-icon:is(.dark *) {
    height: 1rem;
    width: 1rem;
    color: hsl(84.8 85.2% 34.5%);
}
.fi-dropdown-list-item-label {
    font-size: 12px;
    line-height: 1.2rem;
}
.fi-btn-icon {
    height: 1rem;
    width: 1rem;
}
.fi-header-heading {
    font-size: 1.25rem;
    line-height: 1.75rem;
}
.bg-surface-100 {
    background-color: white;
}
.bg-surface-100:is(.dark *) {
    opacity: 85%;
    background-color: #171717 !important;
}
.border-list-100 {
    border-style: solid !important;
    border-width: 1px !important;
    border-color: #d4d4d8 !important;
}
.bgshortcut:is(.dark *) {
    /*background-color: #242424 !important;*/
    @apply bg-[#242424]/30 backdrop-blur-md ring-1 ring-[hsla(0,0%,100%,.1)] !important;
}
.cta-modal {
    /*background-color: #242424 !important;*/
    @apply bg-white ring-1 ring-gray-400/50 !important;
    box-shadow: 0 10px 13px #00000026, 0 17px 17px #00000021, 0 28px 20px #00000014, 0 44px 22px #00000005, 0 64px 24px #0000, 0 0 0 5px #01213921, 0 0 0 4px #fff, 0 0 0 1px #01213921 !important;
}
.border-list-100:is(.dark *) {
    border-style: solid !important;
    border-width: 1px !important;
    border-color: #3f3f46 !important;
}
.dark select:is(.dark *) option{
    background-color: #1f1f1f; /* Warna hijau gelap */
}
.isactive {
    border-color: #65a30d;
    border-style: solid !important;
    border-width: 1px !important;
    border-radius: 6px;
    padding-inline: 10px;
    padding-block: 2px;
}
.isactive:is(.dark *) {
    border-color: #65a30d;
    border-style: solid !important;
    border-width: 1px !important;
    border-radius: 6px;
    padding-inline: 10px;
    padding-block: 2px;
}
.fi-modal-window {
    @apply bg-white ring-1 ring-gray-400/50 !important;
    box-shadow: 0 10px 13px #00000026, 0 17px 17px #00000021, 0 28px 20px #00000014, 0 44px 22px #00000005, 0 64px 24px #0000, 0 0 0 5px #01213921, 0 0 0 4px #fff, 0 0 0 1px #01213921 !important;
}
.fi-modal-close-overlay {
    background-color: #ffffff !important;
    opacity: 0.1 !important;
}

.fi-no-notification {
    background-color: hsl(84.8 85.2% 34.5%) !important;
}