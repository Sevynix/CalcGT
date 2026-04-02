document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        wlCostPerPack: 0,
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
            surgE: { name: 'Surg-E', count: 5, type: '200/?', icon: '/images/surgery/surg_tools/surge.webp' },
            stitches: { name: 'Surgical Stitches', count: 20, type: '200/?', icon: '/images/surgery/surg_tools/stitches.webp' },
            antibiotics: { name: 'Surgical Antibiotics', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/antibiotics.webp' },
            anesthetic: { name: 'Surgical Anesthetic', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/anesthetic.webp' },
            scalpel: { name: 'Surgical Scalpel', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/scalpel.webp' },
            sponge: { name: 'Surgical Sponge', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/sponge.webp' },
            antiseptic: { name: 'Surgical Antiseptic', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/antiseptic.webp' },
            clamp: { name: 'Surgical Clamp', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/clamp.webp' },
            defibrillator: { name: 'Surgical Defibrillator', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/defibrillator.webp' },
            labKit: { name: 'Surgical Lab Kit', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/labkit.webp' },
            pins: { name: 'Surgical Pins', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/pins.webp' },
            splint: { name: 'Surgical Splint', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/splint.webp' },
            transfusion: { name: 'Surgical Transfusion', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/transfusion.webp' },
            ultrasound: { name: 'Surgical Ultrasound', count: 20, type: 'per wl', icon: '/images/surgery/surg_tools/ultrasound.webp' }
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
                this.items[key].type = (key === 'surgE' || key === 'stitches') ? '200/?' : 'per wl';
            }
        }
    }));
});
