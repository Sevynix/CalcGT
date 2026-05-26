document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        wlCostPerPack: 0,
        stocks: {
            charcoal: null,
            red: null,
            green: null,
            purple: null,
            blue: null,
            aqua: null,
            yellow: null,
            varnish: null,
            paintbrush: null,
        },
        prices: {
            charcoal: null,
            red: null,
            green: null,
            purple: null,
            blue: null,
            aqua: null,
            yellow: null,
            varnish: null,
            paintbrush: null,
        },
        items: {
            charcoal: { name: 'Paint Bucket - Charcoal', type: 'per wl', icon: '/images/paint/c.png' },
            red: { name: 'Paint Bucket - Red', type: 'per wl', icon: '/images/paint/red.png' },
            green: { name: 'Paint Bucket - Green', type: 'per wl', icon: '/images/paint/g.png' },
            purple: { name: 'Paint Bucket - Purple', type: 'per wl', icon: '/images/paint/p.png' },
            blue: { name: 'Paint Bucket - Blue', type: 'per wl', icon: '/images/paint/b.png' },
            aqua: { name: 'Paint Bucket - Aqua', type: 'per wl', icon: '/images/paint/a.png' },
            yellow: { name: 'Paint Bucket - Yellow', type: 'per wl', icon: '/images/paint/y.png' },
            varnish: { name: 'Paint Bucket - Varnish', type: 'per wl', icon: '/images/paint/v.png' },
            paintbrush: { name: 'Paintbrush (rare)', type: 'wl/item', icon: '/images/paint/brush.png' },
        },

        getItemRevenue(key) {
            let item = this.items[key];
            let val = parseFloat(this.prices[key]);
            if (!val || val <= 0) return 0;

            let totalItems = this.stocks[key] || 0;
            if (totalItems <= 0) return 0;

            if (item.type === 'per wl') {
                return Math.floor(totalItems / val);
            } else if (item.type === '200/?') {
                return Math.floor((totalItems / 200) * val);
            } else if (item.type === 'wl/item') {
                return Math.floor(totalItems * val);
            }
            return 0;
        },
        init() {
            const key = 'gthelp-painter-pack';
            try {
                const saved = localStorage.getItem(key);
                if (saved) {
                    const data = JSON.parse(saved);
                    if (data.items) {
                        for (let k in data.items) {
                            if (this.items && this.items[k] && data.items[k].type !== undefined) {
                                this.items[k].type = data.items[k].type;
                            }
                        }
                    }
                    if (data.numPacks !== undefined) this.numPacks = data.numPacks;
                    if (data.wlCostPerPack !== undefined) this.wlCostPerPack = data.wlCostPerPack;
                    if (data.prices) Object.assign(this.prices, data.prices);
                }
            } catch(e) {}
            // Save every second —  doesn't detect deep object changes
            setInterval(() => this._save(key), 1000);
        },
        _save(key) {
            try {
                localStorage.setItem(key, JSON.stringify({
                    items: this.items,
                    numPacks: this.numPacks,
                    wlCostPerPack: this.wlCostPerPack,
                    prices: this.prices,
                }));
            } catch(e) {}
        },

        get totalCost() {
            return (this.numPacks || 0) * (this.wlCostPerPack || 0);
        },

        get totalRevenue() {
            let rev = 0;
            for (let key in this.items) {
                rev += this.getItemRevenue(key);
            }
            return rev;
        },

        get netProfit() {
            return parseFloat((this.totalRevenue - this.totalCost).toFixed(2));
        },

        get profitPercentage() {
            let cost = this.totalCost;
            if (cost === 0) return this.netProfit > 0 ? '+100.0%' : '+0.0%';
            let pct = (this.netProfit / cost) * 100;
            let sign = pct > 0 ? '+' : '';
            return `${sign}${pct.toFixed(1)}%`;
        },

        formatTotalCost() {
            return `${this.totalCost.toFixed(1).replace(/\.0$/, '')} WL`;
        },

        formatTotalRevenue() {
            return `${this.totalRevenue.toFixed(1).replace(/\.0$/, '')} WL`;
        },

        formatNetProfit() {
            return `${this.netProfit.toFixed(1).replace(/\.0$/, '')} WL`;
        },

        resetTable() {
            this.prices = {};
            this.stocks = {};
        },

        resetCalculator() {
            try { localStorage.removeItem('gthelp-painter-pack'); } catch(e) {}
            this.numPacks = 1;
            this.wlCostPerPack = 0;
            this.stocks = {};
            this.prices = {};
            for (let key in this.items) {
                this.items[key].type = (key === 'paintbrush') ? 'wl/item' : 'per wl';
            }
        }
    }));
});
