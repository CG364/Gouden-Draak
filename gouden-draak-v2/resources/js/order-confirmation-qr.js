import qrcode from './vendor/qrcode-generator';

const container = document.getElementById('order-confirmation-qr');

if (container) {
    // The default byte encoding masks each character to a single byte, which
    // mangles anything outside Latin-1 (e.g. dish names). Every QR scanner
    // assumes UTF-8 for byte-mode payloads, so encode as UTF-8 to match.
    qrcode.stringToBytes = qrcode.stringToBytesFuncs['UTF-8'];

    const qr = qrcode(0, 'M');
    qr.addData(container.dataset.qrText);
    qr.make();
    container.innerHTML = qr.createSvgTag({ cellSize: 6, margin: 2 });
}
