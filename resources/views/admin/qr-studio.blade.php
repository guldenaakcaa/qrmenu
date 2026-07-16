@extends('admin.layouts.app')

@section('title', 'QR Kod Stüdyosu')
@section('header_title', 'QR Kod Stüdyosu')

@section('content')
<div style="display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
    <!-- Ayarlar Paneli -->
    <div class="card" style="flex: 1; min-width: 300px;">
        <h3 style="margin-bottom: 1.5rem; color: var(--text-main); font-size: 1.25rem;">Özelleştirme Seçenekleri</h3>
        
        <div class="form-group">
            <label for="qrColor">QR Kod Rengi</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="color" id="qrColor" value="#0f172a" style="width: 50px; height: 50px; border: none; cursor: pointer; padding: 0; border-radius: 8px;">
                <span style="color: var(--text-muted); font-size: 0.9rem;">Marka renginize uygun bir ton seçin. (Siyah ve Lacivert önerilir)</span>
            </div>
        </div>

        <div class="form-group" style="margin-top: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" id="addLogo" checked style="width: 18px; height: 18px; accent-color: var(--primary-color);">
                <span style="font-weight: 500;">Ortaya Logo ({{ substr($settings->baslik ?? 'C', 0, 1) }}) Ekle</span>
            </label>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px;">QR kodun tam ortasına markanızın ilk harfini veya logonuzu yerleştirir.</p>
        </div>

        <div style="margin-top: 2.5rem; display: flex; flex-direction: column; gap: 1rem;">
            <p style="font-weight: 600; color: var(--text-main);">Matbaa ve Paylaşım Dosyaları</p>
            <button onclick="downloadSVG()" class="btn btn-primary" style="justify-content: center; padding: 0.85rem; font-size: 1rem; width: 100%;">
                <i class="fa-solid fa-bezier-curve"></i> SVG İndir (Matbaa / Vektörel)
            </button>
            <button onclick="downloadPNG()" class="btn btn-secondary" style="justify-content: center; padding: 0.85rem; font-size: 1rem; width: 100%; background-color: #0ea5e9;">
                <i class="fa-solid fa-image"></i> PNG İndir (Sosyal Medya / Web)
            </button>
            <p style="color: var(--text-muted); font-size: 0.8rem; text-align: center;">Not: Dev boyutlu afişler ve cam giydirmeleri için kesinlikle SVG formatını kullanın. Kalitesi bozulmaz.</p>
        </div>
    </div>

    <!-- QR Kod Önizleme -->
    <div class="card" style="flex: 1; min-width: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 400px; background: #f8fafc;">
        <h3 style="margin-bottom: 2rem; color: var(--text-main); font-size: 1.1rem; text-align: center;">Önizleme Stüdyosu</h3>
        <div id="qr-code-canvas" style="background: white; padding: 1.5rem; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
            <!-- QR Code Rendered Here -->
        </div>
        <p style="margin-top: 1.5rem; color: #64748b; font-size: 0.9rem; font-weight: 500;">
            <i class="fa-solid fa-link" style="margin-right: 5px;"></i> Hedef Bağlantı: <a href="{{ route('home') }}" target="_blank" style="color: var(--primary-color);">{{ route('home') }}</a>
        </p>
    </div>
</div>

<!-- QR Code Styling Library -->
<script type="text/javascript" src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
<script>
    const menuUrl = "{{ route('home') }}";
    const shopInitial = "{{ substr($settings->baslik ?? 'C', 0, 1) }}";
    
    const qrCode = new QRCodeStyling({
        width: 300,
        height: 300,
        data: menuUrl,
        margin: 0,
        qrOptions: {
            typeNumber: 0,
            mode: "Byte",
            errorCorrectionLevel: "H"
        },
        imageOptions: {
            hideBackgroundDots: true,
            imageSize: 0.4,
            margin: 10
        },
        dotsOptions: {
            type: "rounded",
            color: "#0f172a"
        },
        backgroundOptions: {
            color: "#ffffff",
        },
        cornersSquareOptions: {
            type: "extra-rounded",
            color: "#0f172a"
        },
        cornersDotOptions: {
            type: "dot",
            color: "#0f172a"
        }
    });

    // We can use a data URI of an SVG for the logo so it renders nicely without an external image request issue
    const createLogoDataUri = (color) => {
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
            <rect width="100" height="100" rx="30" fill="${color}" />
            <text x="50" y="52" font-family="Arial, sans-serif" font-weight="bold" font-size="60" fill="white" text-anchor="middle" dominant-baseline="middle">${shopInitial}</text>
        </svg>`;
        return "data:image/svg+xml;base64," + btoa(unescape(encodeURIComponent(svg)));
    };

    const updateQRCode = () => {
        const color = document.getElementById('qrColor').value;
        const addLogo = document.getElementById('addLogo').checked;

        qrCode.update({
            dotsOptions: { color: color },
            cornersSquareOptions: { color: color },
            cornersDotOptions: { color: color },
            image: addLogo ? createLogoDataUri(color) : ""
        });
    };

    // Initial render
    qrCode.append(document.getElementById("qr-code-canvas"));
    updateQRCode();

    // Event listeners
    document.getElementById('qrColor').addEventListener('input', updateQRCode);
    document.getElementById('addLogo').addEventListener('change', updateQRCode);

    function downloadPNG() {
        qrCode.download({ name: "QRMENU", extension: "png" });
    }

    function downloadSVG() {
        qrCode.download({ name: "QRMENU", extension: "svg" });
    }
</script>
@endsection
