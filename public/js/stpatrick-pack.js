document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        gemsInput: 6000,
        wlCostPerPack: 0,
        stocks: {
            hat: null,
            beard: null,
            suit: null,
            pants: null,
            shoes: null,
            green_beer: null,
            pot_o_gold: null,
        },
        prices: {
            hat: null,
            beard: null,
            suit: null,
            pants: null,
            shoes: null,
            green_beer: null,
            pot_o_gold: null,
        },
        items: {
            hat: { name: 'Leprechaun Hat', type: 'wl/item', icon: 'images/event/st_patrick/hat.png' },
            beard: { name: 'Orange Beard', type: 'wl/item', icon: 'images/event/st_patrick/beard.png' },
            suit: { name: 'Leprechaun Suit', type: 'wl/item', icon: 'images/event/st_patrick/suits.png' },
            pants: { name: 'Green Pants', type: 'wl/item', icon: 'images/event/st_patrick/pants.png' },
            shoes: { name: 'Leprechaun Shoes', type: 'wl/item', icon: 'images/event/st_patrick/shoes.png' },
            green_beer: { name: 'Green Beer', type: 'wl/item', icon: 'images/event/st_patrick/green_beer.png' },
            pot_o_gold: { name: "Pot O' Gold", type: 'wl/item', icon: 'images/event/st_patrick/Pot_o_gold.png' },
        },
        init() {
            const key = 'gthelp-stpatrick-pack';
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
                    if (Math.floor(currentGems / 6000) !== n) {
                        this.gemsInput = n * 6000;
                    }
                    this.stocks.hat = n * 1;
                    this.stocks.beard = n * 1;
                    this.stocks.suit = n * 1;
                    this.stocks.pants = n * 1;
                    this.stocks.shoes = n * 1;
                    this.stocks.green_beer = n * 6;
                    this.stocks.pot_o_gold = n * 1;
                }
            });

            this.$watch('gemsInput', (newVal) => {
                let g = Number(newVal);
                if (g >= 0) {
                    let n = Math.floor(g / 6000);
                    if (this.numPacks !== n) {
                        this.numPacks = n;
                    }
                }
            });

            // Initialize once on load if empty
            if (!this.stocks.hat) this.stocks.hat = this.numPacks * 1;
            if (!this.stocks.beard) this.stocks.beard = this.numPacks * 1;
            if (!this.stocks.suit) this.stocks.suit = this.numPacks * 1;
            if (!this.stocks.pants) this.stocks.pants = this.numPacks * 1;
            if (!this.stocks.shoes) this.stocks.shoes = this.numPacks * 1;
            if (!this.stocks.green_beer) this.stocks.green_beer = this.numPacks * 6;
            if (!this.stocks.pot_o_gold) this.stocks.pot_o_gold = this.numPacks * 1;
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
            try { localStorage.removeItem('gthelp-stpatrick-pack'); } catch (e) { }
            this.numPacks = 1;
            this.gemsInput = 6000;
            this.wlCostPerPack = 0;
            for (let key in this.prices) this.prices[key] = null;
            this.stocks.hat = 1;
            this.stocks.beard = 1;
            this.stocks.suit = 1;
            this.stocks.pants = 1;
            this.stocks.shoes = 1;
            this.stocks.green_beer = 6;
            this.stocks.pot_o_gold = 1;

            if (this.items && this.items.hat) this.items.hat.type = 'wl/item';
            if (this.items && this.items.beard) this.items.beard.type = 'wl/item';
            if (this.items && this.items.suit) this.items.suit.type = 'wl/item';
            if (this.items && this.items.pants) this.items.pants.type = 'wl/item';
            if (this.items && this.items.shoes) this.items.shoes.type = 'wl/item';
            if (this.items && this.items.green_beer) this.items.green_beer.type = 'wl/item';
            if (this.items && this.items.pot_o_gold) this.items.pot_o_gold.type = 'wl/item';
        },
        resetTable() {
            for (let key in this.prices) this.prices[key] = null;
            let n = Number(this.numPacks);
            if (n >= 0) {
                this.stocks.hat = n * 1;
                this.stocks.beard = n * 1;
                this.stocks.suit = n * 1;
                this.stocks.pants = n * 1;
                this.stocks.shoes = n * 1;
                this.stocks.green_beer = n * 6;
                this.stocks.pot_o_gold = n * 1;
            }
            if (this.items && this.items.hat) this.items.hat.type = 'wl/item';
            if (this.items && this.items.beard) this.items.beard.type = 'wl/item';
            if (this.items && this.items.suit) this.items.suit.type = 'wl/item';
            if (this.items && this.items.pants) this.items.pants.type = 'wl/item';
            if (this.items && this.items.shoes) this.items.shoes.type = 'wl/item';
            if (this.items && this.items.green_beer) this.items.green_beer.type = 'wl/item';
            if (this.items && this.items.pot_o_gold) this.items.pot_o_gold.type = 'wl/item';
        }
    }));
});


