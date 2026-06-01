document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        gemsInput: 10000,
        wlCostPerPack: 0,
        stocks: {
            magic_eggs: null,
            golden_egg_shard: null,
            striped: null,
            jagged: null,
            wavey: null,
            checkered: null,
            polka_dots: null,
            dark: null,
            rainbow: null,
        },
        prices: {
            magic_eggs: null,
            golden_egg_shard: null,
            striped: null,
            jagged: null,
            wavey: null,
            checkered: null,
            polka_dots: null,
            dark: null,
            rainbow: null,
        },
        items: {
            magic_eggs: { name: 'Magic Eggs', type: 'wl/item', icon: 'images/event/easter/magic_eggs.png' },
            golden_egg_shard: { name: 'Golden Egg Shard - Middle', type: 'per wl', icon: 'images/event/easter/golden_egg_shard_mid.png' },
            striped: { name: 'Easter Egg - Striped', type: 'wl/item', icon: 'images/event/easter/striped.png' },
            jagged: { name: 'Easter Egg - Jagged', type: 'wl/item', icon: 'images/event/easter/jagged.png' },
            wavey: { name: 'Easter Egg - Wavey', type: 'wl/item', icon: 'images/event/easter/wavey.png' },
            checkered: { name: 'Easter Egg - Checkered', type: 'wl/item', icon: 'images/event/easter/checkered.png' },
            polka_dots: { name: 'Easter Egg - Polka Dots', type: 'wl/item', icon: 'images/event/easter/polka_dots.png' },
            dark: { name: 'Easter Egg - Dark', type: 'wl/item', icon: 'images/event/easter/dark.png' },
            rainbow: { name: 'Easter Egg - Rainbow', type: 'wl/item', icon: 'images/event/easter/rainbow.png' },
        },
        init() {
            const key = 'gthelp-easter-pack';
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

            // Watch for numPacks to auto-calculate gems only
            this.$watch('numPacks', (newVal) => {
                let n = Number(newVal);
                if (n >= 0) {
                    let currentGems = Number(this.gemsInput);
                    if (Math.floor(currentGems / 10000) !== n) {
                        this.gemsInput = n * 10000;
                    }
                    // We DO NOT auto-calculate stocks for Easter because it's completely randomized.
                }
            });

            this.$watch('gemsInput', (newVal) => {
                let g = Number(newVal);
                if (g >= 0) {
                    let n = Math.floor(g / 10000);
                    if (this.numPacks !== n) {
                        this.numPacks = n;
                    }
                }
            });
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
            try { localStorage.removeItem('gthelp-easter-pack'); } catch (e) { }
            this.numPacks = 1;
            this.gemsInput = 10000;
            this.wlCostPerPack = 0;
            for (let key in this.prices) this.prices[key] = null;
            for (let key in this.stocks) this.stocks[key] = null;
            
            if (this.items && this.items.magic_eggs) this.items.magic_eggs.type = 'wl/item';
            if (this.items && this.items.golden_egg_shard) this.items.golden_egg_shard.type = 'per wl';
            if (this.items && this.items.striped) this.items.striped.type = 'wl/item';
            if (this.items && this.items.jagged) this.items.jagged.type = 'wl/item';
            if (this.items && this.items.wavey) this.items.wavey.type = 'wl/item';
            if (this.items && this.items.checkered) this.items.checkered.type = 'wl/item';
            if (this.items && this.items.polka_dots) this.items.polka_dots.type = 'wl/item';
            if (this.items && this.items.dark) this.items.dark.type = 'wl/item';
            if (this.items && this.items.rainbow) this.items.rainbow.type = 'wl/item';
        },
        resetTable() {
            for (let key in this.prices) this.prices[key] = null;
            for (let key in this.stocks) this.stocks[key] = null;
            
            if (this.items && this.items.magic_eggs) this.items.magic_eggs.type = 'wl/item';
            if (this.items && this.items.golden_egg_shard) this.items.golden_egg_shard.type = 'per wl';
            if (this.items && this.items.striped) this.items.striped.type = 'wl/item';
            if (this.items && this.items.jagged) this.items.jagged.type = 'wl/item';
            if (this.items && this.items.wavey) this.items.wavey.type = 'wl/item';
            if (this.items && this.items.checkered) this.items.checkered.type = 'wl/item';
            if (this.items && this.items.polka_dots) this.items.polka_dots.type = 'wl/item';
            if (this.items && this.items.dark) this.items.dark.type = 'wl/item';
            if (this.items && this.items.rainbow) this.items.rainbow.type = 'wl/item';
        }
    }));
});
