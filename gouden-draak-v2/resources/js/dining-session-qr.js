import qrcode from './vendor/qrcode-generator';

const container = document.getElementById('dining-session-qrcode');
const button = document.getElementById('dining-session-qrcode-toggle');

if (container && button) {
    const qr = qrcode(0, 'M');
    qr.addData(container.dataset.url);
    qr.make();
    container.innerHTML = qr.createSvgTag({ cellSize: 6, margin: 2 });

    button.addEventListener('click', () => {
        const isHidden = container.classList.toggle('hidden');
        button.textContent = isHidden ? 'View QR code' : 'Hide QR code';
    });
}
