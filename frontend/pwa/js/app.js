const API_URL = '/backend/api/v1';

document.addEventListener('DOMContentLoaded', () => {
    checkLogin();

    const loginForm = document.getElementById('login-form');
    if(loginForm) loginForm.addEventListener('submit', handleLogin);

    const logoutBtn = document.getElementById('logout-btn');
    if(logoutBtn) logoutBtn.addEventListener('click', handleLogout);

    const scanBtn = document.getElementById('scan-btn');
    if(scanBtn) scanBtn.addEventListener('click', startScanner);

    const stopScanBtn = document.getElementById('stop-scan-btn');
    if(stopScanBtn) stopScanBtn.addEventListener('click', stopScanner);
});

function checkLogin() {
    const jwt = localStorage.getItem('jwt');
    if (jwt) {
        if(document.getElementById('dashboard-section')) {
            showDashboard();
            loadServices();
        }
    } else {
        if(document.getElementById('login-section')) {
            showLogin();
        } else {
            // If on service.html but not logged in, redirect
            window.location.href = 'index.html';
        }
    }
}

function showLogin() {
    document.getElementById('login-section').classList.remove('hidden');
    document.getElementById('dashboard-section').style.display = 'none';
}

function showDashboard() {
    document.getElementById('login-section').classList.add('hidden');
    document.getElementById('dashboard-section').style.display = 'block';

    const user = JSON.parse(localStorage.getItem('user') || '{}');
    document.getElementById('user-name').textContent = user.full_name || 'Chofer';
}

async function handleLogin(e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    try {
        const res = await fetch(`${API_URL}/auth/login.php`, {
            method: 'POST',
            body: JSON.stringify({ username, password })
        });
        const data = await res.json();

        if (res.ok) {
            localStorage.setItem('jwt', data.jwt);
            localStorage.setItem('user', JSON.stringify(data.user));
            showDashboard();
            loadServices();
        } else {
            document.getElementById('login-error').textContent = data.message;
            document.getElementById('login-error').classList.remove('hidden');
        }
    } catch (err) {
        console.error(err);
        document.getElementById('login-error').textContent = "Error de conexión";
        document.getElementById('login-error').classList.remove('hidden');
    }
}

function handleLogout() {
    localStorage.removeItem('jwt');
    localStorage.removeItem('user');
    window.location.href = 'index.html';
}

async function loadServices() {
    const list = document.getElementById('services-list');
    const jwt = localStorage.getItem('jwt');

    try {
        const res = await fetch(`${API_URL}/services/read.php`, {
            headers: { 'Authorization': 'Bearer ' + jwt }
        });
        const data = await res.json();

        list.innerHTML = '';
        if (data.records && data.records.length > 0) {
            data.records.forEach(svc => {
                const item = document.createElement('a');
                item.className = 'list-group-item list-group-item-action';

                let badgeClass = 'bg-secondary';
                if(svc.status === 'completed') badgeClass = 'bg-success';
                if(svc.status === 'failed') badgeClass = 'bg-danger';

                item.innerHTML = `
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${svc.client_name || 'Cliente'}</h6>
                        <span class="badge ${badgeClass}">${svc.status}</span>
                    </div>
                    <p class="mb-1">${svc.service_number}</p>
                    <small>${svc.created_at}</small>
                `;
                list.appendChild(item);
            });
        } else {
            list.innerHTML = '<div class="text-center text-muted">No hay servicios recientes.</div>';
        }
    } catch (err) {
        list.innerHTML = '<div class="text-danger text-center">Error al cargar servicios.</div>';
    }
}

let html5QrcodeScanner;

function startScanner() {
    document.getElementById('reader').classList.remove('hidden');
    document.getElementById('stop-scan-btn').classList.remove('hidden');
    document.getElementById('scan-btn').classList.add('hidden');

    html5QrcodeScanner = new Html5Qrcode("reader");
    html5QrcodeScanner.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        (decodedText, decodedResult) => {
            // Success
            stopScanner();
            // Redirect to service page
            window.location.href = `service.html?qr=${encodeURIComponent(decodedText)}`;
        },
        (errorMessage) => {
            // parse error, ignore usually
        }
    ).catch(err => {
        alert("Error al iniciar cámara: " + err);
        stopScanner();
    });
}

function stopScanner() {
    if(html5QrcodeScanner){
        html5QrcodeScanner.stop().then(() => {
            html5QrcodeScanner.clear();
            document.getElementById('reader').classList.add('hidden');
            document.getElementById('stop-scan-btn').classList.add('hidden');
            document.getElementById('scan-btn').classList.remove('hidden');
        }).catch(err => console.error(err));
    }
}
