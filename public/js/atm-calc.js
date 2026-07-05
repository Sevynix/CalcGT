document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numATMs: null,
        costPerATM: null,
        gemsRate: null,

        init() {
            const key = 'gthelp-atm-calc';
            try {
                const saved = localStorage.getItem(key);
                if (saved) {
                    const data = JSON.parse(saved);
                    if (data.numATMs !== undefined) this.numATMs = data.numATMs;
                    if (data.costPerATM !== undefined) this.costPerATM = data.costPerATM;
                    if (data.gemsRate !== undefined) this.gemsRate = data.gemsRate;
                }
            } catch (e) { }
            // Save every second
            setInterval(() => this._save(key), 1000);
        },

        _save(key) {
            try {
                localStorage.setItem(key, JSON.stringify({
                    numATMs: this.numATMs,
                    costPerATM: this.costPerATM,
                    gemsRate: this.gemsRate
                }));
            } catch (e) { }
        },

        get totalCost() {
            return (this.numATMs || 0) * (this.costPerATM || 0);
        },

        get gemsPerDay() {
            return (this.numATMs || 0) * 11;
        },

        get wlsPerDay() {
            if (this.gemsRate <= 0) return 0;
            return this.gemsPerDay / this.gemsRate;
        },

        get daysToRoi() {
            let dailyWL = this.wlsPerDay;
            if (dailyWL <= 0) return 0;
            return this.totalCost / dailyWL;
        },

        formatTotalCost() {
            return `${this.totalCost.toFixed(2).replace(/\.00$/, '')} WL`;
        },

        formatGemsPerDay() {
            return Math.floor(this.gemsPerDay).toLocaleString() + ' Gems';
        },

        formatWlsPerDay() {
            return `${this.wlsPerDay.toFixed(2).replace(/\.00$/, '')} WL`;
        },

        formatDaysToRoi() {
            let days = this.daysToRoi;
            if (days === 0) return '-';
            return Math.ceil(days) + ' Days';
        },

        resetCalculator() {
            try { localStorage.removeItem('gthelp-atm-calc'); } catch (e) { }
            this.numATMs = null;
            this.costPerATM = null;
            this.gemsRate = null;
        }
    }));
});

