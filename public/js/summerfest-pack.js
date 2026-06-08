document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        gemsInput: 20000,
        wlCostPerPack: 0,
        stocks: {
            beachBlast: null,
            summerSurprise: null,
            fireworks: null,
        },
        prices: {
            beachBlast: null,
            summerSurprise: null,
            fireworks: null,
        },
        items: {
            beachBlast: { name: 'Beach Blast', type: 'wl/item', icon: 'images/event/summer/beach_blast.png' },
            summerSurprise: { name: 'Summer Surprise', type: 'wl/item', icon: 'images/event/summer/summer_surprise.png' },
            fireworks: { name: 'Fireworks', type: 'per wl', icon: 'images/event/summer/fireworks.png' },
        },
        init() {
            const key = 'gthelp-summerfest-pack';
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
                    if (data.gemsInput !== undefined) this.gemsInput = data.gemsInput;
                    if (data.wlCostPerPack !== undefined) this.wlCostPerPack = data.wlCostPerPack;
                    if (data.prices) Object.assign(this.prices, data.prices);
                    if (data.stocks) Object.assign(this.stocks, data.stocks);
                }
            } catch (e) { }
            // Save every second
            setInterval(() => this._save(key), 1000);

            // Watch for numPacks to auto-calculate stocks
            this.$watch('numPacks', (newVal) => {
                let n = Number(newVal);
                if (n >= 0) {
                    let currentGems = Number(this.gemsInput);
                    if (Math.floor(currentGems / 20000) !== n) {
                        this.gemsInput = n * 20000;
                    }
                    this.stocks.beachBlast = n * 1;
                    this.stocks.summerSurprise = n * 1;
                    this.stocks.fireworks = n * 100;
                }
            });

            this.$watch('gemsInput', (newVal) => {
                let g = Number(newVal);
                if (g >= 0) {
                    let n = Math.floor(g / 20000);
                    if (this.numPacks !== n) {
                        this.numPacks = n;
                    }
                }
            });

            // Initialize once on load if empty
            if (!this.stocks.beachBlast) this.stocks.beachBlast = this.numPacks * 1;
            if (!this.stocks.summerSurprise) this.stocks.summerSurprise = this.numPacks * 1;
            if (!this.stocks.fireworks) this.stocks.fireworks = this.numPacks * 100;
        },
        _save(key) {
            try {
                localStorage.setItem(key, JSON.stringify({
                    items: this.items,
                    numPacks: this.numPacks,
                    gemsInput: this.gemsInput,
                    wlCostPerPack: this.wlCostPerPack,
                    prices: this.prices,
                    stocks: this.stocks
                }));
            } catch (e) { }
        },

        get totalCost() {
            return (this.numPacks || 0) * (this.wlCostPerPack || 0);
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
            let p = this.netProfit;
            let sign = p > 0 ? '+' : '';
            return `${sign}${p.toFixed(1).replace(/\.0$/, '')} WL`;
        },
        resetCalculator() {
            try { localStorage.removeItem('gthelp-summerfest-pack'); } catch (e) { }
            this.numPacks = 1;
            this.gemsInput = 20000;
            this.wlCostPerPack = 0;
            for (let key in this.prices) this.prices[key] = null;
            this.stocks.beachBlast = 1;
            this.stocks.summerSurprise = 1;
            this.stocks.fireworks = 100;
            if (this.items && this.items.beachBlast) this.items.beachBlast.type = 'wl/item';
            if (this.items && this.items.summerSurprise) this.items.summerSurprise.type = 'wl/item';
            if (this.items && this.items.fireworks) this.items.fireworks.type = 'per wl';
        },
        resetTable() {
            for (let key in this.prices) this.prices[key] = null;
            let n = Number(this.numPacks);
            if (n >= 0) {
                this.stocks.beachBlast = n * 1;
                this.stocks.summerSurprise = n * 1;
                this.stocks.fireworks = n * 100;
            }
            if (this.items && this.items.beachBlast) this.items.beachBlast.type = 'wl/item';
            if (this.items && this.items.summerSurprise) this.items.summerSurprise.type = 'wl/item';
            if (this.items && this.items.fireworks) this.items.fireworks.type = 'per wl';
        }
    }));
});
