/**
 * Gradient Blob Animations
 * Rastgele hareket eden arka plan blob animasyonları
 */

export function initBlobAnimations() {
    const blobs = document.querySelectorAll('[class*="animate-float-blob-"]');

    if (blobs.length === 0) return;

    blobs.forEach((blob, index) => {
        const animateBlob = () => {
            // Rastgele değerler - daha geniş hareket alanı
            const translateX = (Math.random() * 600 - 300); // -300 ile 300 arası
            const translateY = (Math.random() * 600 - 300); // -300 ile 300 arası
            const scale = 0.90 + (Math.random() * 0.20); // 0.90 ile 1.10 arası
            const duration = 4 + Math.random() * 4; // 8-16 saniye arası

            // Transition süresini ayarla
            blob.style.transition = `transform ${duration}s ease-in-out`;

            // Transform uygula
            blob.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;

            // Animasyon bitince yeni rastgele değerlerle tekrarla
            setTimeout(animateBlob, duration * 1000);
        };

        // Her blob için farklı başlangıç gecikmesi
        setTimeout(animateBlob, index * 500);
    });
}
