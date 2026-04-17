document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        wlCostPerPack: 0,
        prices: {
            fossilBrush: null,
            rockHammer: null,
            rockChisel: null,
            hardhat: null,
            fossilPrep: null,
        },
        items: {
            fossilBrush: { name: 'Fossil Brush', count: 5, type: 'per wl', icon: '/images/paleontologist/brush.png' },
            rockHammer: { name: 'Rock Hammer', count: 1, type: 'per wl', icon: '/images/paleontologist/hammer.png' },
            rockChisel: { name: 'Rock Chisel', count: 1, type: 'per wl', icon: '/images/paleontologist/chisel.png' },
            hardhat: { name: 'BlueHardhat', count: 1, type: 'per wl', icon: '/images/paleontologist/hat.png' },
            fossilPrep: { name: 'Fossil Prep Station', count: 1, type: 'wl/item', icon: '/images/paleontologist/prep.png' },
        },

        get totalCost() {
            return (this.numPacks || 0) * (this.wlCostPerPack || 0);
        },

        getItemRevenue(key) {
            let item = this.items[key];
            let val = parseFloat(this.prices[key]);
            if (!val || val <= 0) return 0;

            let totalItems = (this.numPacks || 0) * item.count;
            if (item.type === 'per wl') {
                return Math.floor(totalItems / val);
            } else if (item.type === '200/?') {
                return Math.floor((totalItems / 200) * val);
            } else if (item.type === 'wl/item') {
                return Math.floor(totalItems * val);
            }
            return 0;
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
            this.prices = {};
            for (let key in this.items) {
                this.items[key].type = (key === 'teleporterCharge') ? '200/?' : 'per wl';
            }
        }
    }));
});
