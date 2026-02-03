const API_URL = '/backend/api/v1';

document.addEventListener('DOMContentLoaded', () => {
    checkLogin();
    const loginForm = document.getElementById('login-form');
    if(loginForm) loginForm.addEventListener('submit', handleLogin);

    const logoutBtn = document.getElementById('logout-btn');
    if(logoutBtn) logoutBtn.addEventListener('click', handleLogout);
});

function checkLogin() {
    const jwt = localStorage.getItem('panel_jwt');
    if (jwt) {
        showDashboard();
        loadData();
    } else {
        showLogin();
    }
}

function showLogin() {
    document.getElementById('login-section').classList.remove('hidden');
    document.getElementById('dashboard-section').style.display = 'none';
}

function showDashboard() {
    document.getElementById('login-section').classList.add('hidden');
    document.getElementById('dashboard-section').style.display = 'block';

    const user = JSON.parse(localStorage.getItem('panel_user') || '{}');
    document.getElementById('user-name').textContent = user.full_name || 'Admin';
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
            // Check role
            if(data.role === 'driver') {
                document.getElementById('login-error').textContent = "Acceso denegado para choferes.";
                document.getElementById('login-error').classList.remove('hidden');
                return;
            }
            localStorage.setItem('panel_jwt', data.jwt);
            localStorage.setItem('panel_user', JSON.stringify(data.user));
            showDashboard();
            loadData();
        } else {
            document.getElementById('login-error').textContent = data.message;
            document.getElementById('login-error').classList.remove('hidden');
        }
    } catch (err) {
        document.getElementById('login-error').textContent = "Error de conexión";
        document.getElementById('login-error').classList.remove('hidden');
    }
}

function handleLogout() {
    localStorage.removeItem('panel_jwt');
    localStorage.removeItem('panel_user');
    showLogin();
}

async function loadData() {
    const jwt = localStorage.getItem('panel_jwt');
    try {
        const res = await fetch(`${API_URL}/services/read.php`, {
            headers: { 'Authorization': 'Bearer ' + jwt }
        });
        const data = await res.json();

        if (data.records) {
            renderTable(data.records);
            updateMetrics(data.records);
            renderChart(data.records);
        }
    } catch (err) {
        console.error("Error loading data", err);
    }
}

function renderTable(records) {
    const tbody = document.querySelector('#services-table tbody');
    tbody.innerHTML = '';

    records.forEach(r => {
        const tr = document.createElement('tr');
        let statusBadge = r.status === 'completed' ? '<span class="badge bg-success">Realizado</span>' :
                          r.status === 'failed' ? '<span class="badge bg-danger">No Realizado</span>' :
                          '<span class="badge bg-warning text-dark">Pendiente</span>';

        tr.innerHTML = `
            <td>${r.service_number || 'Pendiente'}</td>
            <td>${r.client_name || '-'}</td>
            <td>${r.driver_name || '-'}</td>
            <td>${statusBadge}</td>
            <td>${r.created_at}</td>
            <td>
                <button class="btn btn-sm btn-info text-white" onclick="alert('Funcionalidad Detalle en desarrollo')">Ver</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function updateMetrics(records) {
    const total = records.length;
    const completed = records.filter(r => r.status === 'completed').length;
    const failed = records.filter(r => r.status === 'failed').length;

    document.getElementById('total-count').textContent = total;
    document.getElementById('completed-count').textContent = completed;
    document.getElementById('failed-count').textContent = failed;
}

let activityChart = null;

function renderChart(records) {
    const ctx = document.getElementById('activityChart').getContext('2d');

    // Group by date (simple version)
    const counts = {};
    records.forEach(r => {
        const date = r.created_at.split(' ')[0];
        if(!counts[date]) counts[date] = { completed: 0, failed: 0 };
        if(r.status === 'completed') counts[date].completed++;
        if(r.status === 'failed') counts[date].failed++;
    });

    const labels = Object.keys(counts).sort();
    const dataCompleted = labels.map(d => counts[d].completed);
    const dataFailed = labels.map(d => counts[d].failed);

    if(activityChart) activityChart.destroy();

    activityChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Realizados',
                    data: dataCompleted,
                    backgroundColor: '#198754'
                },
                {
                    label: 'No Realizados',
                    data: dataFailed,
                    backgroundColor: '#dc3545'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
}
