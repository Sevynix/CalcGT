document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        wlCostPerPack: 0,
        prices: {
            cow: null,
            chicken: null,
            wheat: null,
            barnBlock: null,
            redWoodWall: null,
            barnDoor: null,
            strawHat: null,
            overalls: null,
            pithfork: null,
            farmgirlHair: null,
            tractor: null,
        },
        items: {
            cow: { name: 'Cow', count: 1, type: 'per wl', icon: '/images/farm/cow.webp'},
            chicken: { name: 'Chicken', count: 1, type: 'per wl', icon: '/images/farm/chicken.webp'},
            wheat: { name: 'Wheat', count: 1, type: 'per wl', icon: '/images/farm/wheat.webp'},
            barnBlock: { name: 'Barn Block', count: 10, type: 'per wl', icon: '/images/farm/barn_block.webp'},
            redWoodWall: { name: 'Red Wood Wall', count: 10, type: 'per wl', icon: '/images/farm/red_wood_wall.webp'},
            barnDoor: { name: 'Barn Door', count: 1, type: 'per wl', icon: '/images/farm/barn_door.webp'},
            strawHat: { name: 'Straw Hat', count: 1, type: 'per wl', icon: '/images/farm/straw_hat.webp'},
            overalls: { name: 'Overalls', count: 1, type: 'per wl', icon: '/images/farm/overalls.webp'},
            pithfork: { name: 'Pithfork', count: 1, type: 'per wl', icon: '/images/farm/pithfork.webp'},
            farmgirlHair: { name: 'Farmgirl Hair', count: 1, type: 'per wl', icon: '/images/farm/farmgirl_hair.webp'},
            tractor: { name: 'Tractor', count: 1, type: 'wl/item', icon: '/images/farm/tractor.webp'},
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
