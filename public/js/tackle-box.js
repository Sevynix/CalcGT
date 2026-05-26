document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        stocks: {
            worm: null,
            shiny: null,
            salmon: null,
            fishingFly: null,
            shrimp: null,
            megaPellet: null,
            uranium: null,
        },
        prices: {
            worm: null,
            shiny: null,
            salmon: null,
            fishingFly: null,
            shrimp: null,
            megaPellet: null,
            uranium: null,
        },
        items: {
            worm: { name: 'Wiggly Worm', type: 'per wl', icon: '/images/fishing/bait/worm.png' },
            shiny: { name: 'Shiny Flashy Thing', type: 'per wl', icon: '/images/fishing/bait/shiny.png' },
            salmon: { name: 'Salmon Eggs', type: 'per wl', icon: '/images/fishing/bait/salmon.png' },
            fishingFly: { name: 'Fishing Fly', type: 'per wl', icon: '/images/fishing/bait/fishing.png' },
            megaPellet: { name: 'Mega-Pellet Bait', type: 'per wl', icon: '/images/fishing/bait/mega.png' },
            uranium: { name: 'Uranium Glowing Lure', type: 'per wl', icon: '/images/fishing/bait/ura.png' },
            shrimp: { name: 'Shrimp Lure', type: 'wl/item', icon: '/images/fishing/bait/shrimp.png' },
        },

        init() {
            const key = 'gthelp-tackle-box';
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
                    if (data.stocks) Object.assign(this.stocks, data.stocks);
                    if (data.enabledCategories) Object.assign(this.enabledCategories, data.enabledCategories);
                }
            } catch(e) {}
            // Save every second — $watch doesn't detect deep object changes
            setInterval(() => this._save(key), 1000);
        },
        _save(key) {
            try {
                localStorage.setItem(key, JSON.stringify({
                    items: this.items,
                    numPacks: this.numPacks,
                    wlCostPerPack: this.wlCostPerPack,
                    prices: this.prices,
                    stocks: this.stocks || {},
                    enabledCategories: this.enabledCategories || {},
                }));
            } catch(e) {}
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

        formatTotalRevenue() {
            return `${this.totalRevenue.toFixed(1).replace(/\.0$/, '')} WL`;
        },

        resetTable() {
            this.prices = {};
            this.stocks = {};
        },

        resetCalculator() {
            try { localStorage.removeItem('gthelp-tackle-box'); } catch(e) {}
            this.stocks = {};
            this.prices = {};
            for (let key in this.items) {
                this.items[key].type = (key === 'worm' || key === 'shiny') ? '200/?' : 'per wl';
            }
        }
    }));
});
