document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        gemsInput: 7500,
        wlCostPerPack: 0,
        stocks: {
            gbc: null,
            valentineCard: null,
        },
        prices: {
            gbc: null,
            valentineCard: null,
        },
        items: {
            gbc: { name: 'Golden Booty Chest', type: 'wl/item', icon: 'images/event/valentine/gbc.png' },
            valentineCard: { name: "Valentine's Card", type: 'per wl', icon: 'images/event/valentine/valentine_card.png' },
        },
        init() {
            const key = 'gthelp-valentine-pack-v2';
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
                    if (Math.floor(currentGems / 7500) !== n) {
                        this.gemsInput = n * 7500;
                    }
                    this.stocks.gbc = n * 5;
                    this.stocks.valentineCard = n * 1;
                }
            });

            this.$watch('gemsInput', (newVal) => {
                let g = Number(newVal);
                if (g >= 0) {
                    let n = Math.floor(g / 7500);
                    if (this.numPacks !== n) {
                        this.numPacks = n;
                    }
                }
            });

            // Initialize once on load if empty
            if (!this.stocks.gbc) this.stocks.gbc = this.numPacks * 5;
            if (!this.stocks.valentineCard) this.stocks.valentineCard = this.numPacks * 1;
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
            try { localStorage.removeItem('gthelp-valentine-pack-v2'); } catch (e) { }
            this.numPacks = 1;
            this.gemsInput = 7500;
            this.wlCostPerPack = 0;
            for (let key in this.prices) this.prices[key] = null;
            this.stocks.gbc = 5;
            this.stocks.valentineCard = 1;
            if (this.items && this.items.gbc) this.items.gbc.type = 'wl/item';
            if (this.items && this.items.valentineCard) this.items.valentineCard.type = 'per wl';
        },
        resetTable() {
            for (let key in this.prices) this.prices[key] = null;
            let n = Number(this.numPacks);
            if (n >= 0) {
                this.stocks.gbc = n * 5;
                this.stocks.valentineCard = n * 1;
            }
            if (this.items && this.items.gbc) this.items.gbc.type = 'wl/item';
            if (this.items && this.items.valentineCard) this.items.valentineCard.type = 'per wl';
        }
    }));
});


