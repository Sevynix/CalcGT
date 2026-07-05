document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        wlCostPerPack: 0,
        stocks: {},
        prices: {
            surgE: null,
            stitches: null,
            antibiotics: null,
            anesthetic: null,
            scalpel: null,
            sponge: null,
            antiseptic: null,
            clamp: null,
            defibrillator: null,
            labKit: null,
            pins: null,
            splint: null,
            transfusion: null,
            ultrasound: null,
        },
        items: {
            surgE: { name: 'Surg-E', count: 5, type: '200/?', icon: '/images/surgery/surge.png' },
            stitches: { name: 'Surgical Stitches', count: 20, type: '200/?', icon: '/images/surgery/surg_tool/stitches.png' },
            antibiotics: { name: 'Surgical Antibiotics', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/antibiotics.png' },
            anesthetic: { name: 'Surgical Anesthetic', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/anesthetic.png' },
            scalpel: { name: 'Surgical Scalpel', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/scalpel.png' },
            sponge: { name: 'Surgical Sponge', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/sponge.png' },
            antiseptic: { name: 'Surgical Antiseptic', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/antiseptic.png' },
            clamp: { name: 'Surgical Clamp', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/clamp.png' },
            defibrillator: { name: 'Surgical Defibrillator', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/defi.png' },
            labKit: { name: 'Surgical Lab Kit', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/labkit.png' },
            pins: { name: 'Surgical Pins', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/pins.png' },
            splint: { name: 'Surgical Splint', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/splint.png' },
            transfusion: { name: 'Surgical Transfusion', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/transfusion.png' },
            ultrasound: { name: 'Surgical Ultrasound', count: 20, type: 'per wl', icon: '/images/surgery/surg_tool/ultrasound.png' },
            loveMallet: { name: 'Love Mallet', count: 10, type: 'wl/item', icon: '/images/surgery/surg_tool/mallet.png', category: 'Valentine\'s Week' }
        },

        init() {
            const key = 'gthelp-surgery-tools';
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

        enabledCategories: {},

        get availableCategories() {
            let cats = new Set();
            for (let key in this.items) {
                let cat = this.items[key].category;
                if (cat) cats.add(cat);
            }
            return Array.from(cats).sort();
        },

        getCategoryStats(cat) {
            let count = 0;
            let icon = null;
            let rev = 0;
            for (let key in this.items) {
                if (this.items[key].category === cat) {
                    count++;
                    if (!icon) icon = this.items[key].icon;
                    if (this.enabledCategories[cat]) {
                        rev += this.getItemRevenue(key);
                    }
                }
            }
            return { count, icon, rev };
        },

        get totalCost() {
            return (this.numPacks || 0) * (this.wlCostPerPack || 0);
        },

        getItemRevenue(key) {
            let item = this.items[key];
            let val = parseFloat(this.prices[key]);
            if (!val || val <= 0) return 0;

            let totalItems = (this.numPacks || 0) * item.count;
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
                let cat = this.items[key].category;
                if (cat && !this.enabledCategories[cat]) continue;
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
            try { localStorage.removeItem('gthelp-surgery-tools'); } catch(e) {}
            this.numPacks = 1;
            this.wlCostPerPack = 0;
            this.prices = {};
            this.enabledCategories = {};
            for (let key in this.items) {
                this.items[key].type = (key === 'surgE' || key === 'stitches') ? '200/?' : 'per wl';
            }
        }
    }));
});


