const API_URL = '/backend/api/v1';
let currentGps = null;

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const qrCode = urlParams.get('qr');

    if (!qrCode) {
        showError("No se proporcionó código QR.");
        return;
    }

    fetchManifest(qrCode);
    startGps();

    document.getElementById('service-form').addEventListener('submit', submitService);
});

async function fetchManifest(qr) {
    const jwt = localStorage.getItem('jwt');
    if(!jwt) { window.location.href = 'index.html'; return; }

    try {
        const res = await fetch(`${API_URL}/manifests/read_by_qr.php`, {
            method: 'POST',
            body: JSON.stringify({ qr_code: qr }),
            headers: { 'Authorization': 'Bearer ' + jwt }
        });

        if (res.ok) {
            const data = await res.json();
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('service-form-container').classList.remove('hidden');

            document.getElementById('manifest-id').value = data.id;
            document.getElementById('client-name').textContent = data.client_name;
            document.getElementById('client-address').textContent = data.address;
            document.getElementById('expected-items').textContent = data.expected_items;
        } else {
            showError("Manifiesto no encontrado o inválido.");
        }
    } catch (err) {
        showError("Error de conexión al buscar manifiesto.");
    }
}

function showError(msg) {
    document.getElementById('loading').classList.add('hidden');
    const errDiv = document.getElementById('error-msg');
    errDiv.textContent = msg;
    errDiv.classList.remove('hidden');
}

function toggleFailureReason() {
    const status = document.getElementById('status').value;
    const failSec = document.getElementById('failure-section');
    if (status === 'failed') {
        failSec.classList.remove('hidden');
        document.getElementById('failure-reason').required = true;
    } else {
        failSec.classList.add('hidden');
        document.getElementById('failure-reason').required = false;
        document.getElementById('failure-reason').value = '';
    }
}

function startGps() {
    if ("geolocation" in navigator) {
        navigator.geolocation.watchPosition(
            (position) => {
                currentGps = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                    accuracy: position.coords.accuracy
                };
                document.getElementById('gps-status').textContent = `📍 Ubicación detectada (Precisión: ${Math.round(currentGps.accuracy)}m)`;
                document.getElementById('gps-status').className = 'alert alert-success py-2';
                document.getElementById('submit-btn').disabled = false;
            },
            (error) => {
                document.getElementById('gps-status').textContent = "⚠️ Error GPS: " + error.message;
                document.getElementById('gps-status').className = 'alert alert-danger py-2';
            },
            { enableHighAccuracy: true, maximumAge: 10000, timeout: 5000 }
        );
    } else {
        document.getElementById('gps-status').textContent = "⚠️ GPS no soportado en este dispositivo.";
        document.getElementById('gps-status').className = 'alert alert-danger py-2';
    }
}

function formatDate(date) {
    const pad = n => n < 10 ? '0' + n : n;
    return date.getFullYear() + '-' +
           pad(date.getMonth() + 1) + '-' +
           pad(date.getDate()) + ' ' +
           pad(date.getHours()) + ':' +
           pad(date.getMinutes()) + ':' +
           pad(date.getSeconds());
}

async function submitService(e) {
    e.preventDefault();
    const jwt = localStorage.getItem('jwt');
    if(!currentGps) {
        alert("Se requiere ubicación GPS para guardar el servicio.");
        return;
    }

    const data = {
        manifest_id: document.getElementById('manifest-id').value,
        status: document.getElementById('status').value,
        notes: document.getElementById('notes').value,
        failure_reason: document.getElementById('failure-reason').value,
        gps_lat: currentGps.lat,
        gps_lng: currentGps.lng,
        gps_accuracy: currentGps.accuracy,
        start_time: formatDate(new Date())
    };

    try {
        const res = await fetch(`${API_URL}/services/create.php`, {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'Authorization': 'Bearer ' + jwt }
        });

        const result = await res.json();
        if (res.ok) {
            const serviceId = result.id;
            // Upload photos if any
            await uploadEvidences(serviceId);

            alert("Servicio guardado: " + result.service_number);
            window.location.href = 'index.html';
        } else {
            alert("Error: " + result.message);
        }
    } catch (err) {
        alert("Error de conexión al guardar.");
    }
}

async function uploadEvidences(serviceId) {
    const input = document.getElementById('evidence-photos');
    if(!input || input.files.length === 0) return;

    const jwt = localStorage.getItem('jwt');
    const files = input.files;

    // Limit check could be here
    for (let i = 0; i < files.length; i++) {
        const formData = new FormData();
        formData.append('service_id', serviceId);
        formData.append('type', 'other');
        formData.append('file', files[i]);

        try {
            await fetch(`${API_URL}/services/upload_evidence.php`, {
                method: 'POST',
                body: formData,
                headers: { 'Authorization': 'Bearer ' + jwt }
            });
        } catch (e) {
            console.error("Upload failed for file " + i, e);
        }
    }
}

window.toggleFailureReason = toggleFailureReason;
