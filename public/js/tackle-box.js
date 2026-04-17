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

        resetCalculator() {
            this.stocks = {};
            this.prices = {};
            for (let key in this.items) {
                this.items[key].type = (key === 'worm' || key === 'shiny') ? '200/?' : 'per wl';
            }
        }
    }));
});
