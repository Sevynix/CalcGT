document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        wlCostPerPack: 0,
        prices: {
            scienceStation: null,
            laboratory: null,
            labCoat: null,
            combover: null,
            zeGoggles: null,
            deathRay: null,
            chemR: null,
            chemG: null,
            chemY: null,
            chemB: null,
            chemP: null,
        },
        items: {
            scienceStation: { name: 'Science Station', count: 1, type: 'wl/item', icon: '/images/science/science_station.png' },
            laboratory: { name: 'Laboratory', count: 1, type: 'per wl', icon: '/images/science/laboratory.png' },
            labCoat: { name: 'Lab Coat', count: 1, type: 'per wl', icon: '/images/science/lab_coat.png' },
            combover: { name: 'Combover', count: 1, type: 'per wl', icon: '/images/science/combover.png' },
            mad_scientist: { name: 'Mad Scientist Hair', count: 1, type: 'per wl', icon: '/images/science/mad_scientist_hair.png' },
            zeGoggles: { name: 'Ze Goggles', count: 1, type: 'per wl', icon: '/images/science/ze_goggles.png' },
            deathRay: { name: 'Death Ray', count: 1, type: 'wl/item', icon: '/images/science/death_ray.png' },
            chemR: { name: 'Chemical R', count: 5, type: 'per wl', icon: '/images/science/chem/r.png' },
            chemG: { name: 'Chemical G', count: 10, type: 'per wl', icon: '/images/science/chem/g.png' },
            chemY: { name: 'Chemical Y', count: 5, type: 'per wl', icon: '/images/science/chem/y.png' },
            chemB: { name: 'Chemical B', count: 5, type: 'per wl', icon: '/images/science/chem/b.png' },
            chemP: { name: 'Chemical P', count: 5, type: 'per wl', icon: '/images/science/chem/p.png' },
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
