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
            charcoal: { name: 'Paint Bucket - Charcoal', type: 'per wl', icon: '/images/paint/charcoal.webp' },
            red: { name: 'Paint Bucket - Red', type: 'per wl', icon: '/images/paint/red.webp' },
            green: { name: 'Paint Bucket - Green', type: 'per wl', icon: '/images/paint/green.webp' },
            purple: { name: 'Paint Bucket - Purple', type: 'per wl', icon: '/images/paint/purple.webp' },
            blue: { name: 'Paint Bucket - Blue', type: 'per wl', icon: '/images/paint/blue.webp' },
            aqua: { name: 'Paint Bucket - Aqua', type: 'per wl', icon: '/images/paint/aqua.webp' },
            yellow: { name: 'Paint Bucket - Yellow', type: 'per wl', icon: '/images/paint/yellow.webp' },
            varnish: { name: 'Paint Bucket - Varnish', type: 'per wl', icon: '/images/paint/varnish.webp' },
            paintbrush: { name: 'Paintbrush (rare)', type: 'wl/item', icon: '/images/paint/paintbrush.webp' },
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

        resetCalculator() {
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
