class DeliveryAreasHandler {
    constructor() {
        this.init();
    }

    init() {
        this.loadAreas();
    }

    async loadAreas() {
        try {
            const response = await fetch('/delivery-areas', {
                headers: { 'Accept': 'application/json' }
            });
            const areas = await response.json();
            this.renderAreas(areas);
        } catch (error) {
            console.error('Error loading delivery areas:', error);
        }
    }

    renderAreas(areas) {
        const grid = document.getElementById('deliveryGrid');
        const empty = document.getElementById('emptyState');

        if (!grid) return;

        if (!areas || areas.length === 0) {
            grid.innerHTML = '';
            if (empty) empty.style.display = 'block';
            return;
        }

        if (empty) empty.style.display = 'none';

        grid.innerHTML = areas.map(area => `
            <div class="delivery-card ${area.is_active ? '' : 'inactive'}">
                <h3>${area.name}</h3>
                <div class="delivery-fee">₱${area.fee}</div>
                <div class="delivery-info">
                    <p>${area.is_active ? 'Available' : 'Currently Unavailable'}</p>
                    ${area.cutoff_time ? `<p>Same-day cutoff: ${area.cutoff_time}</p>` : ''}
                </div>
            </div>
        `).join('');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new DeliveryAreasHandler();
});