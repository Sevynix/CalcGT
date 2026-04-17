document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        wlCostPerPack: 0,
        prices: {
            aiBrain: null,
            cyborgDiplomat: null,
            galactibolt: null,
            gigablaster: null,
            growtonTorpedo: null,
            hyperShields: null,
            quadriscanner: null,
            spaceMeds: null,
            starFuel: null,
            starSupplies: null,
            stellarDocuments: null,
            tacticalDrone: null,
            teleporterCharge: null,
        },
        items: {
            starFuel: { name: 'Star Fuel', count: 25, type: 'per wl', icon: '/images/startopia/startopia_tools/StarFuel.png' },
            aiBrain: { name: 'AI Brain', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/AI.png' },
            cyborgDiplomat: { name: 'Cyborg Diplomat', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/Cyborg.png' },
            galactibolt: { name: 'Galactibolt', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/bolt.png' },
            gigablaster: { name: 'Gigablaster', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/Giga.png' },
            growtonTorpedo: { name: 'Growton Torpedo', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/Torpedo.png' },
            hyperShields: { name: 'HyperShields', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/Shield.png' },
            quadriscanner: { name: 'Quadriscanner', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/Qscan.png' },
            spaceMeds: { name: 'Space Meds', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/Meds.png' },
            starSupplies: { name: 'Star Supplies', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/Supplies.png' },
            stellarDocuments: { name: 'Stellar Documents', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/Doc.png' },
            tacticalDrone: { name: 'Tactical Drone', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/Tac.png' },
            teleporterCharge: { name: 'Teleporter Charge', count: 5, type: '200/?', icon: '/images/startopia/startopia_tools/tele.png' }
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
