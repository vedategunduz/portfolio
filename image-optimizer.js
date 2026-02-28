#!/usr/bin/env node
/**
 * Image Optimization Script
 * PNG, JPG görüntülerini WebP'ye dönüştür ve optimize et
 *
 * Kullanım: node image-optimizer.js
 */

import sharp from 'sharp';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

const IMAGE_DIRS = [
    path.join(__dirname, 'public/images'),
    path.join(__dirname, 'resources/images'),
];

const OPTIMIZATION_CONFIG = {
    webp: {
        quality: 80,
        alphaQuality: 100,
    },
    jpeg: {
        quality: 85,
        progressive: true,
        optimizeScans: true,
    },
    png: {
        compressionLevel: 9,
        adaptiveFiltering: true,
    },
};

async function optimizeImages() {
    console.log('🖼️  Image Optimization Başlatılıyor...\n');

    for (const imageDir of IMAGE_DIRS) {
        if (!fs.existsSync(imageDir)) {
            console.log(`⊘ Klasör bulunamadı: ${imageDir}`);
            continue;
        }

        console.log(`📁 İşleniyor: ${imageDir}`);

        const files = fs.readdirSync(imageDir);
        const imageFiles = files.filter(f => /\.(jpg|jpeg|png|webp)$/i.test(f));

        if (imageFiles.length === 0) {
            console.log('  ✓ Görüntü dosyası yok\n');
            continue;
        }

        for (const file of imageFiles) {
            const inputPath = path.join(imageDir, file);
            const ext = path.extname(file).toLowerCase();
            const basename = path.basename(file, ext);

            try {
                // Orijinal boyut
                const originalStat = fs.statSync(inputPath);
                const originalSize = originalStat.size;

                // WebP versiyonu oluştur
                const webpPath = path.join(imageDir, `${basename}.webp`);
                if (!fs.existsSync(webpPath) && ext !== '.webp') {
                    await sharp(inputPath)
                        .webp(OPTIMIZATION_CONFIG.webp)
                        .toFile(webpPath);

                    const webpStat = fs.statSync(webpPath);
                    const savings = (((originalSize - webpStat.size) / originalSize) * 100).toFixed(1);

                    console.log(`  ✓ ${file} → ${basename}.webp (${savings}% smaller)`);
                }

                // Orijinal dosyayı optimize et
                if (ext === '.jpg' || ext === '.jpeg') {
                    await sharp(inputPath)
                        .jpeg(OPTIMIZATION_CONFIG.jpeg)
                        .toBuffer()
                        .then(data => {
                            const savings = (((originalSize - data.length) / originalSize) * 100).toFixed(1);
                            fs.writeFileSync(inputPath, data);
                            console.log(`  ✓ ${file} optimized (${savings}% smaller)`);
                        });
                } else if (ext === '.png') {
                    await sharp(inputPath)
                        .png(OPTIMIZATION_CONFIG.png)
                        .toBuffer()
                        .then(data => {
                            const savings = (((originalSize - data.length) / originalSize) * 100).toFixed(1);
                            fs.writeFileSync(inputPath, data);
                            console.log(`  ✓ ${file} optimized (${savings}% smaller)`);
                        });
                }
            } catch (error) {
                console.error(`  ✗ Error optimizing ${file}:`, error.message);
            }
        }

        console.log();
    }

    console.log('✅ Image Optimization Tamamlandı!\n');
    console.log('💡 Tavsiyeler:');
    console.log('  1. HTML dosyalarında <picture> tag'ı kullanın:');
    console.log('     <picture>');
    console.log('       <source srcset="image.webp" type="image/webp">');
    console.log('       <img src="image.jpg" alt="...">');
    console.log('     </picture>');
    console.log('  2. Vite'da image optimization plugin ekleyin');
    console.log('  3. CDN üzerinden image delivery yapın');
}

// Check if sharp is installed
try {
    await optimizeImages();
} catch (error) {
    if (error.code === 'MODULE_NOT_FOUND') {
        console.error('❌ sharp paketi bulunamadı!');
        console.error('\nKurulum: npm install --save-dev sharp');
        process.exit(1);
    }
    throw error;
}
