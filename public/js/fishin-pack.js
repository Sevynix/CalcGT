document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        wlCostPerPack: 0,
        prices: {
            fishingrod: null,
            tackle: null,
            fishtank: null,
            fishtankport: null,
            worm: null,
            waterbucket: null,
            detonator: null,
            handdrill: null,
        },
        items: {
            fishingRod: { name: 'Fishing Rod', count: 1, type: 'per wl', icon: '/images/fishing/fishing_pack/fishing_rod.webp' },
            fishTank: { name: 'Fish Tank', count: 10, type: 'per wl', icon: '/images/fishing/fishing_pack/Fish_Tank.webp' },
            fishTankPort: { name: 'Fish Tank Port', count: 1, type: 'per wl', icon: '/images/fishing/fishing_pack/Fish_Tank_Port.webp' },
            worm: { name: 'Wiggly Worm', count: 5, type: 'per wl', icon: '/images/fishing/fishing_pack/Worm.webp' },
            waterBucket: { name: 'Water Bucket', count: 5, type: 'per wl', icon: '/images/fishing/fishing_pack/Water_Bucket.webp' },
            detonator: { name: 'Nuclear Detonator', count: 1, type: 'per wl', icon: '/images/fishing/fishing_pack/Detonator.webp' },
            handDrill: { name: 'Hand Drill', count: 1, type: 'per wl', icon: '/images/fishing/fishing_pack/Hand_Drill.webp' },
            tackle: { name: 'Tackle Box', count: 1, type: 'wl/item', icon: '/images/fishing/fishing_pack/Tackle_box.webp' },
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
