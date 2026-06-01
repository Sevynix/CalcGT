document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        gemsInput: 30000,
        wlCostPerPack: 0,
        stocks: {
            whistle: null,
            block_de_mayo: null,
            lava_pinata: null,
        },
        prices: {
            whistle: null,
            block_de_mayo: null,
            lava_pinata: null,
        },
        items: {
            whistle: { name: 'Pinata Whistle', type: 'wl/item', icon: 'images/event/cinco/whistle.png' },
            block_de_mayo: { name: 'Block de Mayo Block', type: 'wl/item', icon: 'images/event/cinco/block_de_mayo.png' },
            lava_pinata: { name: 'Lava Pinata', type: 'wl/item', icon: 'images/event/cinco/lava_pinata.png' },
        },
        init() {
            const key = 'gthelp-cinco-pack';
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
                    if (Math.floor(currentGems / 30000) !== n) {
                        this.gemsInput = n * 30000;
                    }
                    this.stocks.whistle = n * 5;
                    this.stocks.block_de_mayo = n * 2;
                    // Note: Lava Pinata is chance-based, so we leave its stock alone 
                    // for manual entry, or let it remain at whatever it was.
                }
            });

            this.$watch('gemsInput', (newVal) => {
                let g = Number(newVal);
                if (g >= 0) {
                    let n = Math.floor(g / 30000);
                    if (this.numPacks !== n) {
                        this.numPacks = n;
                    }
                }
            });

            // Initialize once on load if empty
            if (!this.stocks.whistle) this.stocks.whistle = this.numPacks * 5;
            if (!this.stocks.block_de_mayo) this.stocks.block_de_mayo = this.numPacks * 2;
            if (this.stocks.lava_pinata === null) this.stocks.lava_pinata = 0;
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
            try { localStorage.removeItem('gthelp-cinco-pack'); } catch (e) { }
            this.numPacks = 1;
            this.gemsInput = 30000;
            this.wlCostPerPack = 0;
            for (let key in this.prices) this.prices[key] = null;
            this.stocks.whistle = 5;
            this.stocks.block_de_mayo = 2;
            this.stocks.lava_pinata = 0;

            if (this.items && this.items.whistle) this.items.whistle.type = 'wl/item';
            if (this.items && this.items.block_de_mayo) this.items.block_de_mayo.type = 'wl/item';
            if (this.items && this.items.lava_pinata) this.items.lava_pinata.type = 'wl/item';
        },
        resetTable() {
            for (let key in this.prices) this.prices[key] = null;
            let n = Number(this.numPacks);
            if (n >= 0) {
                this.stocks.whistle = n * 5;
                this.stocks.block_de_mayo = n * 2;
                this.stocks.lava_pinata = 0;
            }
            if (this.items && this.items.whistle) this.items.whistle.type = 'wl/item';
            if (this.items && this.items.block_de_mayo) this.items.block_de_mayo.type = 'wl/item';
            if (this.items && this.items.lava_pinata) this.items.lava_pinata.type = 'wl/item';
        }
    }));
});
