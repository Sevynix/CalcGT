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
            starFuel: { name: 'Star Fuel', count: 25, type: 'per wl', icon: '/images/startopia/startopia_tools/starfuel.png' },
            aiBrain: { name: 'AI Brain', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/ai.png' },
            cyborgDiplomat: { name: 'Cyborg Diplomat', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/cyborg.png' },
            galactibolt: { name: 'Galactibolt', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/bolt.png' },
            gigablaster: { name: 'Gigablaster', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/giga.png' },
            growtonTorpedo: { name: 'Growton Torpedo', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/torpedo.png' },
            hyperShields: { name: 'HyperShields', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/shield.png' },
            quadriscanner: { name: 'Quadriscanner', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/qscan.png' },
            spaceMeds: { name: 'Space Meds', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/meds.png' },
            starSupplies: { name: 'Star Supplies', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/supplies.png' },
            stellarDocuments: { name: 'Stellar Documents', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/doc.png' },
            tacticalDrone: { name: 'Tactical Drone', count: 5, type: 'per wl', icon: '/images/startopia/startopia_tools/tac.png' },
            teleporterCharge: { name: 'Teleporter Charge', count: 5, type: '200/?', icon: '/images/startopia/startopia_tools/tele.png' }
        },
        init() {
            const key = 'gthelp-gala-tools';
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

        resetTable() {
            this.prices = {};
            this.stocks = {};
        },

        resetCalculator() {
            try { localStorage.removeItem('gthelp-gala-tools'); } catch(e) {}
            this.numPacks = 1;
            this.wlCostPerPack = 0;
            this.prices = {};
            for (let key in this.items) {
                this.items[key].type = (key === 'teleporterCharge') ? '200/?' : 'per wl';
            }
        }
    }));
});

