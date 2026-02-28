<?php
/**
 * Critical CSS Helper
 *
 * Above-the-fold CSS'i inlinelemeye yardımcı
 *
 * Kullanım:
 * @include('critical-css')
 * atau
 * {!! view('critical-css') !!}
 */
?>
<style>
    /* Critical CSS - Above the fold optimizasyon */

    /* Reset & Base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        width: 100%;
        height: auto;
        scroll-behavior: smooth;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #fff;
    }

    /* Navigation Critical Styles */
    #navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: white;
        border-bottom: 1px solid #eee;
        transition: transform 0.3s ease;
        transform: translateY(0);
    }

    nav {
        padding: 1rem 0;
        max-width: 1200px;
        margin: 0 auto;
    }

    nav a {
        text-decoration: none;
        color: #0f172a;
        font-weight: 500;
        padding: 0.5rem 1rem;
        display: inline-block;
        transition: color 0.2s ease;
    }

    nav a:hover {
        color: #667eea;
    }

    /* Hero Section Critical */
    .hero {
        padding-top: 6rem;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .hero h1 {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 1rem;
        color: #0f172a;
    }

    .hero p {
        font-size: 1.25rem;
        color: #666;
        margin-bottom: 2rem;
    }

    /* Button Styles */
    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background-color: #764ba2;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background-color: transparent;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-secondary:hover {
        background-color: #f5f7ff;
    }

    /* Container */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* Grid */
    .grid {
        display: grid;
        gap: 1.5rem;
    }

    .grid-2 {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }

    /* Text Classes */
    h1, h2, h3, h4, h5, h6 {
        font-weight: 600;
        line-height: 1.2;
        margin-bottom: 0.5rem;
    }

    h1 {
        font-size: 2.5rem;
    }

    h2 {
        font-size: 2rem;
    }

    h3 {
        font-size: 1.5rem;
    }

    /* Sections */
    section {
        padding: 4rem 0;
    }

    .section-title {
        text-align: center;
        margin-bottom: 3rem;
    }

    /* Images */
    img {
        max-width: 100%;
        height: auto;
        display: block;
    }

    /* Links */
    a {
        color: #667eea;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    a:hover {
        color: #764ba2;
    }

    /* Focus visible */
    *:focus-visible {
        outline: 2px solid #667eea;
        outline-offset: 2px;
    }

    /* Mobile Menu Critical */
    #mobile-menu-button {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
    }

    #mobile-menu {
        display: none;
    }

    @media (max-width: 768px) {
        #mobile-menu-button {
            display: block;
        }

        .hero h1 {
            font-size: 2rem;
        }

        nav {
            display: none;
            flex-direction: column;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        #mobile-menu.max-h-\[500px\] {
            max-height: 500px;
        }

        nav a {
            display: block;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
    }

    /* Remove FOUC (Flash of Unstyled Content) */
    [x-cloak] {
        display: none !important;
    }

    /* Loading state */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
</style>
